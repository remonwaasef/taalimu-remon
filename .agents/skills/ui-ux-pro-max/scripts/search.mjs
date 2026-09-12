#!/usr/bin/env node
/**
 * UI/UX Pro Max Search (Node.js version for environments without Python)
 * Usage:
 *   node search.mjs "<query>" [--domain <domain>] [--stack <stack>] [--max-results 3]
 */

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const DATA_DIR = path.resolve(__dirname, '../data');

const CSV_CONFIG = {
  style: {
    file: 'styles.csv',
    search_cols: ['Style ID', 'Style Category', 'Aliases', 'Keywords', 'Best For', 'Type', 'AI Prompt Keywords'],
    output_cols: ['Style ID', 'Style Category', 'Type', 'Keywords', 'Primary Colors', 'Effects & Animation', 'Best For', 'AI Prompt Keywords', 'CSS/Technical Keywords']
  },
  color: {
    file: 'colors.csv',
    search_cols: ['Product Type', 'Notes'],
    output_cols: ['Product Type', 'Primary', 'Secondary', 'Accent', 'Background', 'Foreground', 'Card', 'Muted', 'Border', 'Notes']
  },
  chart: {
    file: 'charts.csv',
    search_cols: ['Data Type', 'Keywords', 'Best Chart Type', 'When to Use', 'When NOT to Use', 'Accessibility Notes'],
    output_cols: ['Data Type', 'Best Chart Type', 'Secondary Options', 'When to Use', 'When NOT to Use', 'Color Guidance', 'Library Recommendation']
  },
  landing: {
    file: 'landing.csv',
    search_cols: ['Pattern ID', 'Pattern Name', 'Aliases', 'Keywords', 'Conversion Optimization', 'Section Order'],
    output_cols: ['Pattern ID', 'Pattern Name', 'Keywords', 'Section Order', 'Primary CTA Placement', 'Color Strategy', 'Conversion Optimization']
  },
  product: {
    file: 'products.csv',
    search_cols: ['Product Type', 'Keywords', 'Primary Style Recommendation', 'Key Considerations'],
    output_cols: ['Product Type', 'Keywords', 'Primary Style Recommendation', 'Secondary Styles', 'Landing Page Pattern', 'Dashboard Style (if applicable)', 'Color Palette Focus']
  },
  ux: {
    file: 'ux-guidelines.csv',
    search_cols: ['Category', 'Issue', 'Description', 'Platform'],
    output_cols: ['Category', 'Issue', 'Platform', 'Description', 'Do', "Don't", 'Code Example Good', 'Code Example Bad', 'Severity']
  },
  typography: {
    file: 'typography.csv',
    search_cols: ['Font Pair ID', 'Primary Font', 'Secondary Font', 'Category', 'Mood/Style', 'Best For', 'Keywords'],
    output_cols: ['Font Pair ID', 'Primary Font', 'Secondary Font', 'Mood/Style', 'Best For', 'Google Fonts Import', 'CSS Variables']
  },
  icons: {
    file: 'icons.csv',
    search_cols: ['Category', 'Icon Name', 'Aliases', 'Keywords', 'Best For'],
    output_cols: ['Icon Name', 'Category', 'Phosphor Icon', 'Heroicon', 'Lucide Icon', 'Keywords', 'Best For']
  }
};

function parseCSV(content) {
  const lines = [];
  let currentLine = [];
  let currentField = '';
  let inQuotes = false;

  for (let i = 0; i < content.length; i++) {
    const char = content[i];
    const nextChar = content[i + 1];

    if (char === '"') {
      if (inQuotes && nextChar === '"') {
        currentField += '"';
        i++;
      } else {
        inQuotes = !inQuotes;
      }
    } else if (char === ',' && !inQuotes) {
      currentLine.push(currentField.trim());
      currentField = '';
    } else if ((char === '\r' || char === '\n') && !inQuotes) {
      if (char === '\r' && nextChar === '\n') i++;
      currentLine.push(currentField.trim());
      if (currentLine.some(f => f.length > 0)) {
        lines.push(currentLine);
      }
      currentLine = [];
      currentField = '';
    } else {
      currentField += char;
    }
  }
  if (currentField || currentLine.length > 0) {
    currentLine.push(currentField.trim());
    if (currentLine.some(f => f.length > 0)) lines.push(currentLine);
  }
  if (lines.length === 0) return [];

  const headers = lines[0];
  return lines.slice(1).map(row => {
    const obj = {};
    headers.forEach((h, idx) => {
      obj[h] = row[idx] || '';
    });
    return obj;
  });
}

function scoreRow(row, searchCols, queryTerms) {
  let score = 0;
  const searchableText = searchCols.map(c => row[c] || '').join(' ').toLowerCase();

  for (const term of queryTerms) {
    if (searchableText.includes(term)) {
      score += 1;
      const regex = new RegExp(`\\b${term}\\b`, 'i');
      if (regex.test(searchableText)) score += 2;
    }
  }
  return score;
}

function runSearch() {
  const args = process.argv.slice(2);
  if (args.length === 0 || args.includes('--help') || args.includes('-h')) {
    console.log(`UI/UX Pro Max Node Search
Usage: node search.mjs "<query>" [--domain <domain>] [--stack <stack>] [--max-results <n>]
Domains: ${Object.keys(CSV_CONFIG).join(', ')}
Stacks: laravel, html-tailwind, react, nextjs, vue, ...`);
    process.exit(0);
  }

  let query = '';
  let domain = 'style';
  let stack = '';
  let maxResults = 3;

  for (let i = 0; i < args.length; i++) {
    if (args[i] === '--domain' || args[i] === '-d') {
      domain = args[++i];
    } else if (args[i] === '--stack' || args[i] === '-s') {
      stack = args[++i];
    } else if (args[i] === '--max-results' || args[i] === '-n') {
      maxResults = parseInt(args[++i], 10) || 3;
    } else if (!args[i].startsWith('-') && !query) {
      query = args[i];
    }
  }

  const queryTerms = (query || '').toLowerCase().split(/\s+/).filter(Boolean);

  if (stack) {
    const stackFile = path.join(DATA_DIR, 'stacks', `${stack.toLowerCase()}.csv`);
    if (!fs.existsSync(stackFile)) {
      console.error(`Stack file not found: ${stackFile}`);
      process.exit(1);
    }
    const rows = parseCSV(fs.readFileSync(stackFile, 'utf8'));
    const scored = rows.map(r => ({
      row: r,
      score: scoreRow(r, ['Guideline', 'Description', 'Do', "Don't", 'Category'], queryTerms)
    })).filter(x => queryTerms.length === 0 || x.score > 0)
      .sort((a, b) => b.score - a.score)
      .slice(0, maxResults);

    console.log(`\n## UI Pro Max Stack Guidelines: ${stack}\n**Query:** "${query}" | **Found:** ${scored.length} results\n`);
    scored.forEach((res, idx) => {
      const r = res.row;
      console.log(`### Result ${idx + 1}: ${r['Guideline'] || r['Category']}`);
      console.log(`- **Category:** ${r['Category']}`);
      console.log(`- **Description:** ${r['Description']}`);
      console.log(`- **Do:** ${r['Do']}`);
      console.log(`- **Don't:** ${r["Don't"]}`);
      if (r['Code Good']) console.log(`- **Good Code:** \`${r['Code Good']}\``);
      console.log('');
    });
    return;
  }

  const cfg = CSV_CONFIG[domain] || CSV_CONFIG.style;
  const filePath = path.join(DATA_DIR, cfg.file);
  if (!fs.existsSync(filePath)) {
    console.error(`Data file not found: ${filePath}`);
    process.exit(1);
  }

  const rows = parseCSV(fs.readFileSync(filePath, 'utf8'));
  const scored = rows.map(r => ({
    row: r,
    score: scoreRow(r, cfg.search_cols, queryTerms)
  })).filter(x => queryTerms.length === 0 || x.score > 0)
    .sort((a, b) => b.score - a.score)
    .slice(0, maxResults);

  console.log(`\n## UI Pro Max Search Results (${domain})\n**Query:** "${query}" | **Found:** ${scored.length} results\n`);
  scored.forEach((res, idx) => {
    console.log(`### Result ${idx + 1}`);
    const r = res.row;
    cfg.output_cols.forEach(col => {
      if (r[col]) {
        console.log(`- **${col}:** ${r[col]}`);
      }
    });
    console.log('');
  });
}

runSearch();
