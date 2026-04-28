import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionApplicationController::bulkAdd
 * @see app/Http/Controllers/ActionApplicationController.php:724
 * @route '/action/applications/{id}/interviews/bulk-add'
 */
export const bulkAdd = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkAdd.url(args, options),
    method: 'post',
})

bulkAdd.definition = {
    methods: ["post"],
    url: '/action/applications/{id}/interviews/bulk-add',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::bulkAdd
 * @see app/Http/Controllers/ActionApplicationController.php:724
 * @route '/action/applications/{id}/interviews/bulk-add'
 */
bulkAdd.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                }

    return bulkAdd.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::bulkAdd
 * @see app/Http/Controllers/ActionApplicationController.php:724
 * @route '/action/applications/{id}/interviews/bulk-add'
 */
bulkAdd.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkAdd.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::bulkAdd
 * @see app/Http/Controllers/ActionApplicationController.php:724
 * @route '/action/applications/{id}/interviews/bulk-add'
 */
    const bulkAddForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkAdd.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::bulkAdd
 * @see app/Http/Controllers/ActionApplicationController.php:724
 * @route '/action/applications/{id}/interviews/bulk-add'
 */
        bulkAddForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkAdd.url(args, options),
            method: 'post',
        })
    
    bulkAdd.form = bulkAddForm
/**
* @see \App\Http\Controllers\ActionApplicationController::bulkDelete
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{applicationId}/interviews/bulk-delete'
 */
export const bulkDelete = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkDelete.url(args, options),
    method: 'post',
})

bulkDelete.definition = {
    methods: ["post"],
    url: '/action/applications/{applicationId}/interviews/bulk-delete',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::bulkDelete
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{applicationId}/interviews/bulk-delete'
 */
bulkDelete.url = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { applicationId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    applicationId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicationId: args.applicationId,
                }

    return bulkDelete.definition.url
            .replace('{applicationId}', parsedArgs.applicationId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::bulkDelete
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{applicationId}/interviews/bulk-delete'
 */
bulkDelete.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkDelete.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::bulkDelete
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{applicationId}/interviews/bulk-delete'
 */
    const bulkDeleteForm = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkDelete.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::bulkDelete
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{applicationId}/interviews/bulk-delete'
 */
        bulkDeleteForm.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkDelete.url(args, options),
            method: 'post',
        })
    
    bulkDelete.form = bulkDeleteForm
/**
* @see \App\Http\Controllers\ActionApplicationController::decision
 * @see app/Http/Controllers/ActionApplicationController.php:863
 * @route '/action/applications/{applicationId}/interviews/{interviewId}/decision'
 */
export const decision = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: decision.url(args, options),
    method: 'post',
})

decision.definition = {
    methods: ["post"],
    url: '/action/applications/{applicationId}/interviews/{interviewId}/decision',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::decision
 * @see app/Http/Controllers/ActionApplicationController.php:863
 * @route '/action/applications/{applicationId}/interviews/{interviewId}/decision'
 */
decision.url = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    applicationId: args[0],
                    interviewId: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicationId: args.applicationId,
                                interviewId: args.interviewId,
                }

    return decision.definition.url
            .replace('{applicationId}', parsedArgs.applicationId.toString())
            .replace('{interviewId}', parsedArgs.interviewId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::decision
 * @see app/Http/Controllers/ActionApplicationController.php:863
 * @route '/action/applications/{applicationId}/interviews/{interviewId}/decision'
 */
decision.post = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: decision.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::decision
 * @see app/Http/Controllers/ActionApplicationController.php:863
 * @route '/action/applications/{applicationId}/interviews/{interviewId}/decision'
 */
    const decisionForm = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: decision.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::decision
 * @see app/Http/Controllers/ActionApplicationController.php:863
 * @route '/action/applications/{applicationId}/interviews/{interviewId}/decision'
 */
        decisionForm.post = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: decision.url(args, options),
            method: 'post',
        })
    
    decision.form = decisionForm
/**
* @see \App\Http\Controllers\ActionApplicationController::bulkUpdateSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:613
 * @route '/action/applications/{applicationId}/interviews/bulk-update-schedule'
 */
export const bulkUpdateSchedule = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkUpdateSchedule.url(args, options),
    method: 'post',
})

bulkUpdateSchedule.definition = {
    methods: ["post"],
    url: '/action/applications/{applicationId}/interviews/bulk-update-schedule',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::bulkUpdateSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:613
 * @route '/action/applications/{applicationId}/interviews/bulk-update-schedule'
 */
bulkUpdateSchedule.url = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { applicationId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    applicationId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicationId: args.applicationId,
                }

    return bulkUpdateSchedule.definition.url
            .replace('{applicationId}', parsedArgs.applicationId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::bulkUpdateSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:613
 * @route '/action/applications/{applicationId}/interviews/bulk-update-schedule'
 */
bulkUpdateSchedule.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkUpdateSchedule.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::bulkUpdateSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:613
 * @route '/action/applications/{applicationId}/interviews/bulk-update-schedule'
 */
    const bulkUpdateScheduleForm = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkUpdateSchedule.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::bulkUpdateSchedule
 * @see app/Http/Controllers/ActionApplicationController.php:613
 * @route '/action/applications/{applicationId}/interviews/bulk-update-schedule'
 */
        bulkUpdateScheduleForm.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkUpdateSchedule.url(args, options),
            method: 'post',
        })
    
    bulkUpdateSchedule.form = bulkUpdateScheduleForm
const interviews = {
    bulkAdd: Object.assign(bulkAdd, bulkAdd),
bulkDelete: Object.assign(bulkDelete, bulkDelete),
decision: Object.assign(decision, decision),
bulkUpdateSchedule: Object.assign(bulkUpdateSchedule, bulkUpdateSchedule),
}

export default interviews