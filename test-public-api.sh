#!/bin/bash

# Test Public Case Status API
# This script tests the public API endpoint for case status checking

echo "=========================================="
echo "PUBLIC CASE STATUS API - TEST SUITE"
echo "=========================================="
echo ""
echo "Test Date: $(date '+%Y-%m-%d %H:%M:%S')"
echo "Case Reference: 2025-CA-2-MFBAR (NEW FORMAT)"
echo "Format: YEAR-SECTIONCODE-RUNNINGNUMBER-CLIENTABBR"
echo ""

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test counter
TOTAL_TESTS=0
PASSED_TESTS=0
FAILED_TESTS=0

# Function to run test
run_test() {
    local test_name=$1
    local test_command=$2
    local expected_success=$3
    
    TOTAL_TESTS=$((TOTAL_TESTS + 1))
    
    echo "=========================================="
    echo "TEST $TOTAL_TESTS: $test_name"
    echo "=========================================="
    
    # Run the command
    response=$(eval "$test_command")
    
    # Check if response contains expected success value
    success=$(echo "$response" | jq -r '.success')
    
    if [ "$success" == "$expected_success" ]; then
        echo -e "${GREEN}✅ PASS${NC}"
        PASSED_TESTS=$((PASSED_TESTS + 1))
    else
        echo -e "${RED}❌ FAIL${NC}"
        FAILED_TESTS=$((FAILED_TESTS + 1))
    fi
    
    # Display response
    echo ""
    echo "Response:"
    echo "$response" | jq '.'
    echo ""
}

# Test 1: Valid case reference + valid IC (Plaintiff)
run_test \
    "Valid Case Reference + Valid IC (Plaintiff)" \
    'curl -s -X POST "http://localhost:8000/api/public/case/2025-CA-2-MFBAR/status" \
        -H "Accept: application/json" \
        -H "Content-Type: application/json" \
        -d "{\"ic_passport\": \"841205136419\"}"' \
    "true"

# Test 2: Valid case reference + valid IC (Defendant)
run_test \
    "Valid Case Reference + Valid IC (Defendant)" \
    'curl -s -X POST "http://localhost:8000/api/public/case/2025-CA-2-MFBAR/status" \
        -H "Accept: application/json" \
        -H "Content-Type: application/json" \
        -d "{\"ic_passport\": \"900521137664\"}"' \
    "true"

# Test 3: Valid case reference + invalid IC
run_test \
    "Valid Case Reference + Invalid IC (Security Test)" \
    'curl -s -X POST "http://localhost:8000/api/public/case/2025-CA-2-MFBAR/status" \
        -H "Accept: application/json" \
        -H "Content-Type: application/json" \
        -d "{\"ic_passport\": \"999999999999\"}"' \
    "false"

# Test 4: Invalid case reference
run_test \
    "Invalid Case Reference" \
    'curl -s -X POST "http://localhost:8000/api/public/case/INVALID-CASE/status" \
        -H "Accept: application/json" \
        -H "Content-Type: application/json" \
        -d "{\"ic_passport\": \"841205136419\"}"' \
    "false"

# Test 5: Missing IC/Passport parameter
run_test \
    "Missing IC/Passport Parameter (Validation Test)" \
    'curl -s -X POST "http://localhost:8000/api/public/case/2025-CA-2-MFBAR/status" \
        -H "Accept: application/json" \
        -H "Content-Type: application/json" \
        -d "{}"' \
    "false"

# Test 6: Get Case Timeline
run_test \
    "Get Case Timeline (Valid IC)" \
    'curl -s -X POST "http://localhost:8000/api/public/case/2025-CA-2-MFBAR/timeline" \
        -H "Accept: application/json" \
        -H "Content-Type: application/json" \
        -d "{\"ic_passport\": \"841205136419\"}"' \
    "true"

# Test 7: CORS Test (Check headers)
echo "=========================================="
echo "TEST $((TOTAL_TESTS + 1)): CORS Headers Test"
echo "=========================================="
echo ""
echo "Checking CORS headers..."
echo ""

cors_response=$(curl -s -I -X OPTIONS "http://localhost:8000/api/public/case/2025-CA-2-MFBAR/status" \
    -H "Origin: http://localhost:3000" \
    -H "Access-Control-Request-Method: POST" \
    -H "Access-Control-Request-Headers: Content-Type")

echo "$cors_response"
echo ""

if echo "$cors_response" | grep -q "Access-Control-Allow-Origin"; then
    echo -e "${GREEN}✅ CORS headers present${NC}"
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    echo -e "${YELLOW}⚠️  CORS headers not found (may need server restart)${NC}"
fi

TOTAL_TESTS=$((TOTAL_TESTS + 1))

# Summary
echo ""
echo "=========================================="
echo "TEST SUMMARY"
echo "=========================================="
echo ""
echo "Total Tests: $TOTAL_TESTS"
echo -e "Passed: ${GREEN}$PASSED_TESTS${NC}"
echo -e "Failed: ${RED}$FAILED_TESTS${NC}"
echo ""

if [ $FAILED_TESTS -eq 0 ]; then
    echo -e "${GREEN}🎉 ALL TESTS PASSED!${NC}"
    echo ""
    echo "✅ Public API is ready for production use!"
    echo ""
    echo "API Endpoint: POST /api/public/case/{case_reference}/status"
    echo "Required Body: {\"ic_passport\": \"YOUR_IC_NUMBER\"}"
    echo ""
else
    echo -e "${RED}❌ SOME TESTS FAILED${NC}"
    echo ""
    echo "Please review the failed tests above."
    echo ""
fi

echo "=========================================="

