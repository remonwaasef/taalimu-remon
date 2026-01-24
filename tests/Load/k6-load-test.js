import http from 'k6/http';
import { check, sleep, group } from 'k6';
import { NetworkStats } from 'k6/experimental/stats';

export const options = {
    scenarios: {
        browsing_traffic: {
            executor: 'ramping-vus',
            startVUs: 0,
            stages: [
                { duration: '5m', target: 50000 },   // Warm up to 50k
                { duration: '10m', target: 200000 }, // Load to 200k
                { duration: '15m', target: 500000 }, // Peak to 500k
                { duration: '10m', target: 500000 }, // Sustain peak
                { duration: '10m', target: 0 },      // Ramp down
            ],
            gracefulRampDown: '5m',
        },
    },
    thresholds: {
        http_req_duration: ['p(95)<1000'], // 95% of requests must be under 1s (relaxed for high load)
        http_req_failed: ['rate<0.05'],    // Error rate < 5% under extreme load
        'http_req_duration{scenario:browsing_traffic}': ['p(99)<2000'],
    },
    // Adding resource limits if supported by execution environment
    discardResponseBodies: true, // Save memory by discarding bodies (mostly)
};

const BASE_URL = __ENV.BASE_URL || 'http://demo-center.localhost:8000';

// Headers for JSON API requests
const params = {
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
};

export default function () {
    group('Authentication Flow', function () {
        // 1. Visit Login Page to get CSRF (Simulated)
        let res = http.get(`${BASE_URL}/login`);

        check(res, {
            'login page status is 200': (r) => r.status === 200,
            'response time < 2s': (r) => r.timings.duration < 2000
        });

        sleep(Math.random() * 2 + 1); // User types credentials

        // 2. Perform Login
        // Note: For 500k users, you should ideally pre-generate tokens or use a simplified auth bypass
        // to test the APPLICATION logic, not the hashing algorithm (bcrypt is slow).
        // Here we simulate a successful login redirect.
        res = http.post(`${BASE_URL}/login`, JSON.stringify({
            email: 'admin@demo.com',
            password: 'password',
        }), params);

        check(res, {
            'login request received': (r) => r.status === 200 || r.status === 302,
        });
    });

    group('Admin Dashboard & Operations', function () {
        sleep(Math.random() * 3 + 2); // Think time

        // 3. Admin Dashboard (Heavy Aggregations)
        let res = http.get(`${BASE_URL}/center/dashboard`, params);
        check(res, {
            'dashboard loaded': (r) => r.status === 200,
        });

        sleep(Math.random() * 2 + 1);

        // 4. Students List (Pagination)
        res = http.get(`${BASE_URL}/center/students?page=1`, params);
        check(res, {
            'students list loaded': (r) => r.status === 200,
        });

        // 5. Search for a Student (Filter Query)
        sleep(1);
        res = http.get(`${BASE_URL}/center/students?search=Ahmed`, params);
        check(res, {
            'search performed': (r) => r.status === 200,
        });
    });

    sleep(Math.random() * 10 + 5); // Pacing
}
