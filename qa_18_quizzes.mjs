import { newBrowser, check, getResults, dumpJson, recordError } from './qa_helpers.mjs';
import { execSync } from 'node:child_process';

const { browser, page } = await newBrowser();
const B = 'http://qa-center-194003.localhost:8000';
const A = (p) => B + p;
const BASE = 'D:\\new project\\antigravty\\taalimu.com\\taalimu.com';
const php = (script) => execSync('php "' + BASE + '\\qa_db_helpers.php" ' + script, { encoding: 'utf8' }).trim();

page.on('dialog', d => d.accept().catch(() => {}));

const login = async (email, password) => {
  await page.goto(A('/login'), { waitUntil: 'domcontentloaded', timeout: 30000 }).catch(() => {});
  await page.waitForTimeout(2500);
  const tok = await page.evaluate(() => document.querySelector('form input[name=_token]')?.value);
  if (!tok) { await page.fill('input[name=email]', email).catch(() => {}); await page.fill('input[name=password]', password).catch(() => {}); await page.keyboard.press('Enter').catch(() => {}); }
  else {
    const res = await page.evaluate(async ({ tk, em, pw }) => {
      const r = await fetch('/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tk },
        body: new URLSearchParams({ email: em, password: pw, _token: tk }).toString(),
        redirect: 'manual',
      });
      return { status: r.status, loc: r.headers.get('location') };
    }, { tk: tok, em: email, pw: password });
    const target = (res.loc && !res.loc.includes('/login')) ? res.loc : '/';
    await page.goto(A(target), { waitUntil: 'domcontentloaded', timeout: 30000 }).catch(() => {});
  }
  for (let i = 0; i < 25; i++) { await page.waitForTimeout(1000); if (!page.url().includes('/login')) break; }
  return page.url();
};

const qaDb = async (script) => JSON.parse(php(script));

const waitNav = async (ms) => { await page.waitForTimeout(ms || 4000); };

const modalSubmit = async (triggerSel, fieldVals, submitSel) => {
  if (triggerSel) {
    await page.click(triggerSel);
    await page.waitForTimeout(1200);
  }
  for (const [k, v] of Object.entries(fieldVals)) {
    const sel = '[name="' + k + '"]';
    await page.fill(submitSel + ' ' + sel, String(v)).catch(async () => {
      await page.selectOption(submitSel + ' ' + sel, String(v));
    });
  }
  await page.click(submitSel + ' button[type=submit]');
  await waitNav();
};

const TEST_TITLE = 'اختبار QA مؤقت 2';
const NEW_TITLE = 'اختبار QA مؤقت معدل';
let quizId = null;

try {
  php('set_t3_owner_password');
  php('set_t3_student_password');
  php('ensure_t3_lesson');
} catch (e) { console.log('  [dbg] fixture: ' + e.message.slice(0, 80)); }

try {
  const url = await login('qa.owner194003@example.com', 'password');
  check('admin logged in', !url.includes('/login'), 'url=' + url);
} catch (e) { recordError('admin login', e); }

// quizzes index
try {
  const r = await page.goto(A('/quizzes'), { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(2500);
  const body = await page.evaluate(() => document.body.innerText.slice(0, 300)).catch(() => 'nav');
  check('quizzes index accessible', r.status() === 200, 'status=' + r.status() + ' len=' + body.length);
} catch (e) { recordError('quizzes index', e); }

// create quiz (lesson id from fixture)
try {
  const lesson = await qaDb('lesson3');
  const res = await page.evaluate(async ({ ls, t }) => {
    const r = await fetch('/lessons/' + ls + '/quiz', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? document.querySelector('form input[name=_token]')?.value },
      body: new URLSearchParams({ _token: document.querySelector('form input[name=_token]')?.value, title: t, passing_score: '60', duration_minutes: '10' }).toString(),
    });
    return { status: r.status, url: r.url };
  }, { ls: lesson, t: TEST_TITLE });
  const after = await qaDb('lastquiz');
  quizId = String(after.id);
  check('quiz store creates quiz + redirect to edit', res.status === 200 && res.url.includes('/quizzes/' + quizId + '/edit'), 'status=' + res.status + ' url=' + res.url);
  check('quiz persisted in DB', after && after.title === TEST_TITLE && String(after.passing_score) === '60', 'id=' + quizId + ' pass=' + after.passing_score);
} catch (e) { recordError('quiz store', e); }

// edit page + update quiz via form
try {
  if (quizId) {
    const r = await page.goto(A('/quizzes/' + quizId + '/edit'), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(1500);
    check('quiz edit page 200', r.status() === 200, 'status=' + r.status());
    await page.fill('form[action*="quiz"] input[name=title]', NEW_TITLE);
    await page.fill('form[action*="quizzes/' + quizId + '"] input[name=passing_score]', '75');
    await page.click('form[action$="/quizzes/' + quizId + '"] button[type=submit]');
    await waitNav(4000);
    const after = await qaDb('quiz' + quizId);
    check('quiz updated in DB via form', after && after.title === NEW_TITLE && String(after.passing_score) === '75', 'title=' + (after && after.title) + ' pass=' + (after && after.passing_score));
  } else { check('quiz update skipped', false, 'no quizId'); }
} catch (e) { recordError('quiz update', e); }

// add MCQ question -> defaults to 2 options
let qId = null;
try {
  if (quizId) {
    await page.goto(A('/quizzes/' + quizId + '/edit'), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(1500);
    await modalSubmit('button[data-bs-target="#addQuestionModal"]', { content: 'ما هو ناتج 2+2؟ سؤال اختبار QA مؤقت', type: 'mcq', points: '10' }, '#addQuestionModal form');
    const q = await qaDb('qoflast');
    qId = String(q.id);
    const count = await qaDb('opc' + qId);
    check('question stored (mcq) with 2 default options', parseInt(count) === 2, 'q=' + qId + ' options=' + count);
  } else { check('question store skipped', false, 'no quizId'); }
} catch (e) { recordError('question store', e); }

// add option
let optToEdit = null;
try {
  if (qId) {
    await page.goto(A('/quizzes/' + quizId + '/edit'), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(1500);
    await modalSubmit(null, { content: 'خيار إضافي QA 4' }, 'form[action$="/questions/' + qId + '/options"]');
    const o = await qaDb('lastopt' + qId);
    optToEdit = o.id;
    check('option store persisted', o && o.content === 'خيار إضافي QA 4', 'id=' + o.id);
  } else { check('option store skipped', false, 'no qId'); }
} catch (e) { recordError('option store', e); }

// update option content
try {
  if (optToEdit) {
    await modalSubmit(null, { content: 'خيار معدل QA 9' }, 'form[action$="/options/' + optToEdit + '"]');
    const o = await qaDb('opt' + optToEdit);
    check('option updated in DB', o && o.content === 'خيار معدل QA 9', 'content=' + (o && o.content));
  } else { check('option update skipped', false, 'no option'); }
} catch (e) { recordError('option update', e); }

// set correct option (exclusive)
try {
  if (optToEdit) {
    await page.click('form[action$="/options/' + optToEdit + '/correct"] button[type=submit]');
    await waitNav(4000);
    const s = await qaDb('oc' + qId);
    check('exactly one correct option after set', s.correct === 1, JSON.stringify(s));
  } else { check('set correct skipped', false, 'no option'); }
} catch (e) { recordError('set correct', e); }

// update question (edit modal)
try {
  if (qId) {
    await modalSubmit('button[data-bs-target="#editQuestionModal-' + qId + '"]', { content: 'نص سؤال معدل QA: ما ناتج 7+7؟', points: '15' }, '#editQuestionModal-' + qId + ' form');
    const q = await qaDb('q' + qId);
    check('question updated in DB', q && q.content.includes('7+7') && String(q.points) === '15', 'points=' + (q && q.points));
  } else { check('question update skipped', false, 'no qId'); }
} catch (e) { recordError('question update', e); }

// destroy the custom option
try {
  if (optToEdit) {
    await page.click('form[action$="/options/' + optToEdit + '"]:has(input[name=_method][value=DELETE]) button[type=submit]');
    await waitNav(4000);
    const count = await qaDb('opc' + qId);
    check('option destroyed in DB', parseInt(count) === 2, 'options=' + count);
  } else { check('option destroy skipped', false, 'no option'); }
} catch (e) { recordError('option destroy', e); }

// destroy question
try {
  if (qId) {
    await page.click('form[action$="/quiz-questions/' + qId + '"]:has(input[name=_method][value=DELETE]) button[type=submit]');
    await waitNav(4000);
    const qs = await qaDb('qbyquiz' + quizId);
    check('question destroyed in DB', qs.length === 0, 'qs=' + qs.length);
  } else { check('question destroy skipped', false, 'no qId'); }
} catch (e) { recordError('question destroy', e); }

// student attempt: create fresh quiz+question first, then student submissions
try {
  if (quizId) {
    const lesson = await qaDb('lesson3');
    await page.evaluate(async ({ ls }) => {
      const r = await fetch('/lessons/' + ls + '/quiz', {
        method: 'POST',
        redirect: 'manual',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': document.querySelector('form input[name=_token]')?.value },
        body: new URLSearchParams({ _token: document.querySelector('form input[name=_token]')?.value, title: 'اختبار QA للطالب', passing_score: '50', duration_minutes: '30' }).toString(),
      });
      return r.status;
    }, { ls: lesson });
    const sQuiz = await qaDb('lastquiz');
    await page.goto(A('/quizzes/' + sQuiz.id + '/edit'), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(1500);
    await modalSubmit('button[data-bs-target="#addQuestionModal"]', { content: 'سؤال بسيط للطالب QA: هل الأرض كروية؟', type: 'true_false', points: '10' }, '#addQuestionModal form');
    await waitNav(4000);
    const q = await qaDb('qoflast');
    const sqId = String(q.id);
    const opts = await qaDb('opts' + sqId);
    const correctOpt = opts.find(o => o.is_correct);
    const optId = correctOpt ? correctOpt.id : opts[0].id;
    const url = await login('mhmd.aaly.demo0@qa-center-194003', 'password');
    check('student logged in', !url.includes('/login'), 'url=' + url);
    const r2 = await page.goto(A('/quizzes/' + sQuiz.id), { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(2500);
    check('student quiz page accessible', r2.status() === 200, 'status=' + r2.status());
    const radioName = 'answers[' + sqId + ']';
    await page.check('input[name="' + radioName + '"][value="' + optId + '"]');
    const sub = await page.evaluate(async ({ qid, name, oid }) => {
      const tok = document.querySelector('form#quizForm input[name=_token]')?.value || document.querySelector('form input[name=_token]')?.value;
      if (!tok) return { ok: false, why: 'no token' };
      const r = await fetch('/quizzes/' + qid + '/submit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': tok },
        body: new URLSearchParams({ _token: tok, [name]: String(oid) }).toString(),
        redirect: 'follow',
      });
      return { ok: true, status: r.status, url: r.url };
    }, { qid: sQuiz.id, name: radioName, oid: optId });
    await waitNav(3500);
    let resultUrl = (sub.url && sub.url.includes('quiz-attempts')) ? sub.url : null;
    if (resultUrl) {
      await page.goto(resultUrl, { waitUntil: 'domcontentloaded', timeout: 30000 }).catch(() => {});
      await page.waitForTimeout(2500);
    }
    const afterSubmit = await page.evaluate(() => ({ url: location.href, txt: document.body.innerText.slice(0, 200) })).catch(() => ({ url: 'nav', txt: '' }));
    check('quiz submit accepted (result page)', sub.ok === true && (sub.status === 200 || afterSubmit.url.includes('quiz-attempts')), JSON.stringify(sub) + ' -> ' + afterSubmit.url);
    check('result content rendered', afterSubmit.txt.length > 50, 'len=' + afterSubmit.txt.length + ' url=' + afterSubmit.url);
  } else { check('student attempt skipped', false, 'no quiz'); }
} catch (e) { recordError('student attempt', e); }

// cleanup: remove created quizzes
try {
  if (quizId) {
    const r = await qaDb('cleanupt3quizzes');
    check('cleanup: QA quizzes removed', r === true || r === 'true', 'res=' + r);
  }
} catch (e) { recordError('cleanup', e); }

await dumpJson('quizzes');
await browser.close();
const results = getResults();
console.log('QUIZZES DONE. Passed: ' + results.checks.filter(c => c.passed).length + '/' + results.checks.length);