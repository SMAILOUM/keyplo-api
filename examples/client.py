"""EN: Server-side KEYPLO account example. RU: Серверный пример API аккаунта."""
import json, os, urllib.request, urllib.error
key = os.environ["KEYPLO_API_KEY"]
for endpoint in ("me", "balance"):
    request = urllib.request.Request("https://keyplo.com/api/v1/" + endpoint,
        headers={"Authorization": "Bearer " + key, "Accept": "application/json"})
    try:
        with urllib.request.urlopen(request, timeout=20) as response:
            print(endpoint, json.load(response))
    except urllib.error.HTTPError as error:
        print("HTTP", error.code, error.read().decode("utf-8"))
        raise SystemExit(1)
