<?php

/**
 * Simple test script for the Spellcheck API
 * Usage: php test_spellcheck.php
 */

require_once __DIR__ . '/SpellcheckService.php';
require_once __DIR__ . '/SpellcheckConfig.php';

function testSpellcheckService() {
    echo "Testing Spellcheck Service...\n";
    echo "============================\n\n";
    
    try {
        $service = new SpellcheckService();
        
        // Test 1: Check correct Portuguese word
        echo "Test 1: Checking correct word 'casa'\n";
        $result = $service->checkWord('casa');
        echo "Result: " . ($result ? 'CORRECT' : 'INCORRECT') . "\n\n";
        
        // Test 2: Check incorrect word
        echo "Test 2: Checking incorrect word 'xyzabc'\n";
        $result = $service->checkWord('xyzabc');
        echo "Result: " . ($result ? 'CORRECT' : 'INCORRECT') . "\n\n";
        
        // Test 3: Get suggestions
        echo "Test 3: Getting suggestions for 'casa'\n";
        $suggestions = $service->getSuggestions('casa');
        echo "Suggestions: " . implode(', ', $suggestions) . "\n\n";
        
        // Test 4: Check text
        echo "Test 4: Checking text 'Esta casa está bonita'\n";
        $results = $service->checkText('Esta casa está bonita');
        foreach ($results as $result) {
            echo "Word: '{$result['word']}' - " . ($result['correct'] ? 'CORRECT' : 'INCORRECT') . "\n";
        }
        echo "\n";
        
        // Test 5: Available languages
        echo "Test 5: Available languages\n";
        $languages = $service->getAvailableLanguages();
        echo "Languages: " . implode(', ', $languages) . "\n\n";
        
        echo "All tests completed successfully!\n";
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

function testAPIEndpoint() {
    echo "\nTesting API Endpoint...\n";
    echo "======================\n\n";
    
    // Simulate API requests
    $testCases = [
        ['action' => 'check', 'word' => 'casa'],
        ['action' => 'suggest', 'word' => 'xyzabc'],
        ['action' => 'languages'],
        ['action' => 'check_text', 'text' => 'Esta casa está bonita']
    ];
    
    foreach ($testCases as $i => $testCase) {
        echo "API Test " . ($i + 1) . ": " . json_encode($testCase) . "\n";
        
        // Simulate the API call by setting $_REQUEST
        $_REQUEST = $testCase;
        
        ob_start();
        try {
            include __DIR__ . '/spellcheck_api.php';
            $output = ob_get_clean();
            $response = json_decode($output, true);
            
            if ($response && $response['success']) {
                echo "Result: SUCCESS\n";
                if (isset($response['data'])) {
                    echo "Data: " . json_encode($response['data']) . "\n";
                }
            } else {
                echo "Result: FAILED\n";
                if (isset($response['error'])) {
                    echo "Error: " . $response['error'] . "\n";
                }
            }
        } catch (Exception $e) {
            ob_end_clean();
            echo "Exception: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
}

// Run tests if script is executed directly
if (php_sapi_name() === 'cli') {
    testSpellcheckService();
    testAPIEndpoint();
}