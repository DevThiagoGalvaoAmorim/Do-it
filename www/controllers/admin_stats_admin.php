<?php
// admin_stats_controller.php

require_once 'safe_page.php';
require_once 'models/admin_stats_model.php';

$userCount = getUser Count();
$notesCreated = getNotesCreated();
$newUsersPerMonth = getNewUsersPerMonth();
$noteTypesUsage = getNoteTypesUsage();
?>