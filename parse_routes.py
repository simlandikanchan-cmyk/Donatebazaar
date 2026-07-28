import json, sys

# Parse routes.json
with open("C:/xampp/htdocs/fundraise/routes.json", "rb") as f:
    raw = f.read()
text = raw.decode("utf-16-le")
data = json.loads(text)
print(f"Total routes in routes.json: {len(data)}")
print()

# Find privacy/legal routes
print("=== Routes matching privacy/legal/terms/cookies/refund ===")
for r in data:
    uri = r.get("uri", "")
    name = r.get("name", "")
    if any(k in uri.lower() for k in ["privacy", "legal", "terms", "cookies", "refund"]):
        method = r.get("method", "")
        controller = r.get("controller", "")
        print(f"  {method:6s} {uri:40s} name={name:30s} controller={controller}")

print()
print("=== All routes with /privacy in URI ===")
for r in data:
    uri = r.get("uri", "")
    if "privacy" in uri.lower():
        print(f"  {r.get(\"method\",\"\")} {uri} -> {r.get(\"controller\",\"\")}")

