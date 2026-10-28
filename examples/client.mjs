// EN: Server-side account example. RU: Серверный пример API аккаунта.
const key = process.env.KEYPLO_API_KEY;
if (!key) throw new Error('Set KEYPLO_API_KEY / Задайте KEYPLO_API_KEY');
for (const endpoint of ['me', 'balance']) {
  const response = await fetch(`https://keyplo.com/api/v1/${endpoint}`, {
    headers: { Authorization: `Bearer ${key}`, Accept: 'application/json' },
    signal: AbortSignal.timeout(20000),
  });
  const body = await response.json();
  if (!response.ok) { console.error(response.status, body); process.exitCode = 1; break; }
  console.log(endpoint, body);
}
