<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Phrases
    |--------------------------------------------------------------------------
    |
    | Usage examples:
    | - Flash messages
    | - Help text
    | - Success / warning / error messages
    |
    | Text should:
    | - Use sentence case
    | - Only contain one sentence
    | - Not be terminated with a full stop (.)
    */

    // Copyright & Licensing
    'is-copyright' => 'is ©',
    'and-licensed-under' => 'and licensed under',
    'powered-by-steam' => 'Powered by Steam',

    // User interface
    'toggle-navigation' => 'Toggle navigation',

    // Account Authentication
    'profile-update-required' => 'Please update your profile to continue',
    'please-sign-in' => 'Please sign in through Steam',
    'no-steam-account' => 'Don\'t have a Steam account? No problem!',
    'create-steam-account' => 'Create a Steam account for free',
    'provider-not-supported' => 'Unsupported authentication provider ":provider"',
    'user-successfully-logged-in' => 'User :username successfully logged in',
    'user-successfully-logged-out' => 'User :username successfully logged out',

    // Roles
    'user-already-has-role' => ':user has already been assigned the role :role',
    'role-successfully-assigned' => ':user has been assigned the role :role',
    'role-successfully-unassigned' => ':user is no longer assigned the role :role',
    'cannot-assign-role-to-self' => 'You cannot assign roles to yourself',
    'cannot-unassign-role-from-self' => 'You cannot unassign roles from yourself',
    'cannot-assign-role-to-super-admin' => 'Roles cannot be assigned to Super Admins',

    // Steam Apps Import
    'requesting-apps-from-steam' => 'Requesting all apps from Steam',
    'preparing-data' => 'Preparing data',
    'importing-apps-from-steam' => 'Importing :count apps from Steam into database',
    'updating-apps-in-db-and-adding-new' => 'Updating :count apps already in database and adding new apps from Steam',
    'import-complete' => 'Steam App import complete',

    // Steam States Import
    'no-steam-users-to-import' => 'No Steam users to import',
    'requesting-current-status-of-count-users-from-steam' => 'Requesting current status of :count users from Steam',
    'requesting-games-owned-by-count-users-from-steam' => 'Requesting games owned by :count users from Steam',
    'unable-to-import-state-for-user' => 'Unable to import user state for user :id - :error',
    'successfully-imported-states-for-x-of-y-users' => 'Successfully imported states for :x of :y users from Steam',
    'successfully-imported-apps-for-x-of-y-users' => 'Successfully imported apps for :x of :y users from Steam',
    'unable-to-import-apps-for-user' => 'Unable to import apps for user :username - :error',

    // General purpose
    'item-created' => ':Item successfully created',
    'no-items-found' => 'No :item found',
    'the-following-errors-were-encountered' => 'The following errors were encountered',
    'oh-no' => 'Oh no!',

    // Logs
    'minimum-level' => 'Minimum level',
    'mark-as-read' => 'Mark as Read',
    'log-entries-marked-as-read' => 'Log entries successfully marked as read',
    'paste-below-into-github-issue' => 'Paste the below into a GitHub issue',

    // User profile
    'steam-profile-private' => 'Your Steam game details are currently private.',
    'please-consider-public-visibility' => 'This means games you play won\'t show in ' . config(
            'app.name'
        ) . ' with everyone else. Please consider making your game details public, even if it\'s just for the event. Thanks!',
    'edit-steam-profile' => 'Edit Steam Profile',

    // Games
    'x-in-game' => ':x In Game',
    'view-game-in-steam-store' => 'View :game in the Steam Store',
    'logo-for-game' => 'Logo for :game',

];