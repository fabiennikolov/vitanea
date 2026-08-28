#!/usr/bin/env bash
#
# Пуска временен публичен линк към локалния сайт.
#
# Quick тунелите на Cloudflare са безакаунтни и БЕЗ гаранция за работа —
# адресът е случаен, сменя се при всяко пускане и Cloudflare може да го
# спре по всяко време. За стабилен адрес трябва named tunnel (виж docs).
#
set -euo pipefail

PORT="${1:-8000}"
LOG="/tmp/vitanea-tunnel.log"

command -v cloudflared >/dev/null || { echo "cloudflared липсва: brew install cloudflared"; exit 1; }

# Сайтът трябва да е подсигурен, преди да излезе навън.
if grep -q '^APP_DEBUG=true' .env; then
  echo "⚠️  APP_DEBUG=true — stack traces ще са публични. Спри го преди да споделяш."
  exit 1
fi

pkill -f "cloudflared tunnel" 2>/dev/null || true
sleep 1

: > "$LOG"
nohup cloudflared tunnel --url "http://127.0.0.1:${PORT}" > "$LOG" 2>&1 &

printf "изчаквам адреса"
for _ in $(seq 1 30); do
  URL=$(grep -oE "https://[a-z0-9-]+\.trycloudflare\.com" "$LOG" | head -1 || true)
  [ -n "${URL:-}" ] && break
  printf "."
  sleep 1
done
echo

if [ -z "${URL:-}" ]; then
  echo "не успя. Логът:"; tail -20 "$LOG"; exit 1
fi

echo "$URL" | pbcopy 2>/dev/null && COPIED=" (копиран в клипборда)" || COPIED=""
echo "$URL$COPIED"
