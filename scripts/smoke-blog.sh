#!/usr/bin/env bash

set -uo pipefail

BASE_URL="${BASE_URL:-http://localhost}"
failures=0

fail() {
    echo "FAIL: $*"
    failures=$((failures + 1))
}

ok() {
    echo "OK: $*"
}

check_status() {
    local expected="$1"
    local path="$2"
    local code

    if ! code=$(curl -sS -o /dev/null -w '%{http_code}' "$BASE_URL$path"); then
        fail "curl $path"
        return
    fi

    if [ "$code" = "$expected" ]; then
        ok "$code $path"
    else
        fail "$code (expected $expected) $path"
    fi
}

check_markup() {
    local path="$1"
    local needle="$2"
    local label="$3"

    if curl -sS "$BASE_URL$path" | grep -qF "$needle"; then
        ok "$label"
    else
        fail "$label ($path)"
    fi
}

check_status 200 "/"
check_status 200 "/blog/"
check_status 200 "/blog/category/tech/"
check_status 200 "/blog/page/2/"
check_status 404 "/blog/page/999/"
check_status 404 "/blog/no-such-article/"
check_status 404 "/blog/category/no-such-section/"

check_markup "/blog/" 'class="post-card"' "listing renders cards"
check_markup "/blog/" 'rel="canonical"' "listing has canonical"
check_markup "/blog/" 'class="chip__count"' "category counters render"

if curl -sS "$BASE_URL/blog/" | grep -q 'src=""'; then
    fail "listing contains empty image src"
else
    ok "listing has no empty image src"
fi

detail_path=$(curl -sS "$BASE_URL/blog/" \
    | grep -oE 'href="/blog/[^"/]+/"' \
    | head -1 \
    | sed 's/^href="//; s/"$//')

if [ -n "$detail_path" ]; then
    check_status 200 "$detail_path"
else
    fail "cannot extract detail URL from listing"
fi

tag_path=$(curl -sS "$BASE_URL${detail_path:-/blog/}" \
    | grep -oE 'href="/blog/\?tag=[^"]+"' \
    | head -1 \
    | sed 's/^href="//; s/"$//')

if [ -n "$tag_path" ]; then
    check_status 200 "$tag_path"

    if curl -sS "$BASE_URL$tag_path" | grep -qE 'class="post-card"|class="hero-card"'; then
        ok "tag filter returns articles"
    else
        fail "tag filter returned no articles ($tag_path)"
    fi
else
    fail "cannot extract tag URL from detail page"
fi

search_path="/blog/?q=%D1%80%D0%B5%D0%B4%D0%B0%D0%BA%D1%86"
check_status 200 "$search_path"

if curl -sS "$BASE_URL$search_path" | grep -qF 'name="robots" content="noindex, follow"'; then
    ok "search page is noindex"
else
    fail "search page is not noindex"
fi

if [ "$failures" -ne 0 ]; then
    echo "Smoke tests failed: $failures"
    exit 1
fi

echo "All smoke tests passed."
