#!/bin/bash

# Test Case Reference API Endpoints
# Case Reference: 2025-08-1APP7I

BASE_URL="http://localhost:8000"
CASE_REF="2025-08-1APP7I"
COOKIE_FILE="cookies.txt"

echo "=========================================="
echo "Testing Case Reference API"
echo "Case Reference: $CASE_REF"
echo "=========================================="
echo ""

# Step 1: Login to get session
echo "Step 1: Logging in..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/login" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -c "$COOKIE_FILE" \
  -d '{
    "email": "admin@naeelah.com",
    "password": "password"
  }')

echo "Login Response:"
echo "$LOGIN_RESPONSE" | jq '.' 2>/dev/null || echo "$LOGIN_RESPONSE"
echo ""
echo "=========================================="
echo ""

# Step 2: Test Case Info API
echo "Step 2: Testing GET /api/case/$CASE_REF/info"
echo "URL: $BASE_URL/api/case/$CASE_REF/info"
echo ""
curl -s -X GET "$BASE_URL/api/case/$CASE_REF/info" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -b "$COOKIE_FILE" | jq '.'
echo ""
echo "=========================================="
echo ""

# Step 3: Test Timeline API
echo "Step 3: Testing GET /api/case/$CASE_REF/timeline"
echo "URL: $BASE_URL/api/case/$CASE_REF/timeline"
echo ""
curl -s -X GET "$BASE_URL/api/case/$CASE_REF/timeline" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -b "$COOKIE_FILE" | jq '.'
echo ""
echo "=========================================="
echo ""

# Step 4: Test Timeline API with filters
echo "Step 4: Testing GET /api/case/$CASE_REF/timeline with filters"
echo "URL: $BASE_URL/api/case/$CASE_REF/timeline?status=active&per_page=5"
echo ""
curl -s -X GET "$BASE_URL/api/case/$CASE_REF/timeline?status=active&per_page=5" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -b "$COOKIE_FILE" | jq '.'
echo ""
echo "=========================================="
echo ""

# Step 5: Test Documents API
echo "Step 5: Testing GET /api/case/$CASE_REF/documents"
echo "URL: $BASE_URL/api/case/$CASE_REF/documents"
echo ""
curl -s -X GET "$BASE_URL/api/case/$CASE_REF/documents" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -b "$COOKIE_FILE" | jq '.'
echo ""
echo "=========================================="
echo ""

# Step 6: Test Financial API
echo "Step 6: Testing GET /api/case/$CASE_REF/financial"
echo "URL: $BASE_URL/api/case/$CASE_REF/financial"
echo ""
curl -s -X GET "$BASE_URL/api/case/$CASE_REF/financial" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -b "$COOKIE_FILE" | jq '.'
echo ""
echo "=========================================="
echo ""

# Cleanup
rm -f "$COOKIE_FILE"

echo "✅ API Testing Complete!"
echo ""

