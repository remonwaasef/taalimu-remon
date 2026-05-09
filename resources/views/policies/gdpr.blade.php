@extends('layouts.landing-new')

@section('title', 'GDPR Compliance & Privacy Policy')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center mt-5">
        <div class="col-md-10 bg-white p-5 rounded shadow-sm text-dark">
            <h1 class="mb-4">GDPR Compliance & Privacy Information</h1>
            <p class="text-muted">Last Updated: {{ date('F j, Y') }}</p>

            <h3 class="mt-4">1. Commitment to Data Protection</h3>
            <p>We are fully committed to compliance with the General Data Protection Regulation (GDPR) and ensuring the security and protection of the personal information that we process.</p>

            <h3 class="mt-4">2. Data Protection Officer (DPO)</h3>
            <p>To ensure strict adherence to GDPR and to address any concerns regarding your privacy, we have appointed a dedicated Data Protection Officer. You may contact our DPO for any inquiries regarding your data:</p>
            <div class="bg-light p-4 rounded border">
                <strong>Name:</strong> DPO Office - Taalimu Educational Platform<br>
                <strong>Email:</strong> <a href="mailto:dpo@taalimu.com">dpo@taalimu.com</a><br>
                <strong>Phone:</strong> +20 12 1306 5205<br>
            </div>

            <h3 class="mt-4">3. Data Subject Rights</h3>
            <p>Under the GDPR, you have the right to:</p>
            <ul>
                <li><strong>Access:</strong> Request copies of your personal data.</li>
                <li><strong>Rectification:</strong> Request correction of inaccurate data.</li>
                <li><strong>Erasure ("Right to be Forgotten"):</strong> Request deletion of your data under certain conditions.</li>
                <li><strong>Data Portability:</strong> Request transfer of your data to another organization.</li>
            </ul>

            <h3 class="mt-4">4. Data Breach Policy</h3>
            <p>In the unlikely event of a data breach, our DPO will notify the relevant supervisory authority within 72 hours, and communicate directly with affected users if the breach poses a high risk to their rights and freedoms.</p>
        </div>
    </div>
</div>
@endsection
