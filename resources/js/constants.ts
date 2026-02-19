// resources/js/constants.ts

// Menu permissions for different user roles
export const MENU_PERMISSIONS: Record<string, readonly number[]> = {
    '/user': [1],
    '/application-tracker': [1, 2, 3, 5, 6],
    '/action': [1, 2, 3, 5, 6],
    '/action/batches': [1, 2, 3],
    '/action/schedules': [1, 2, 3],
    '/action/applicants': [1, 2, 3, 5, 6],
    '/action/applications': [1, 2, 3, 5, 6],
    '/intermediate': [1, 2, 3, 5, 6],
    '/intermediate/projects': [1, 5],
    '/intermediate/requests': [1, 5],
    '/intermediate/applicants': [1, 2, 3, 5, 6],
    '/intermediate/applications': [1, 2, 3, 5, 6],
}

// Links that are visible but disabled for certain permissions
export const DISABLED_LINKS: Record<string, number[]> = {
    '/application-tracker': [6],
    '/action': [5, 6],
    '/intermediate': [6],
}
