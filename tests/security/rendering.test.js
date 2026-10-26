const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const context = vm.createContext({});
vm.runInContext(fs.readFileSync('public/assets/js/security.js', 'utf8'), context);
for (const payload of ['<img src=x onerror=alert(1)>', '" onmouseover="alert(1)', "' autofocus onfocus='alert(1)", '&lt;script&gt;']) {
    const safe = context.escapeRecord({ detail: payload, pc_id: 1 });
    assert(!/[<>"']/.test(safe.detail));
    assert.equal(safe.pc_id, 1);
}
assert.equal(context.escapeHtml('<>&"\''), '&lt;&gt;&amp;&quot;&#39;');
for (const file of ['app/Views/pages/Pc/ListPC.php', 'app/Views/pages/Pc/logPC.php']) {
    const source = fs.readFileSync(file, 'utf8');
    assert.match(source, /function addTableRow\(pc\) \{\s*pc = escapeRecord\(pc\);/);
}
console.log('Rendering security checks passed');

for (const file of ['app/Views/pages/Pc/ListPC.php', 'app/Views/pages/Pc/logPC.php']) {
    const source = fs.readFileSync(file, 'utf8');
    const start = source.indexOf('function addTableRow(pc) {');
    const end = source.indexOf('tbody.appendChild(row);', start) + 'tbody.appendChild(row);'.length;
    let html = '';
    context.document = { createElement: () => ({}) };
    context.tbody = { appendChild: row => { html = row.innerHTML; } };
    vm.runInContext(source.slice(start, end) + '\n}', context);
    context.addTableRow({
        pc_id: 1, use_status: 'A', user_name: '<img src=x onerror=alert(1)>',
        computer_name: '<svg onload=alert(1)>', ip_address: '" onmouseover="alert(1)',
        detail: '<img src=x onerror=alert(1)>', userid: '<svg onload=alert(1)>'
    });
    assert(!html.includes('<img') && !html.includes('<svg'));
    assert(!html.includes('data-ip="" onmouseover='));
}
console.log('Actual table renderers safely escaped stored payloads');
