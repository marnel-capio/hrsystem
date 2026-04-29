import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::index
 * @see app/Http/Controllers/IntermediateInterviewerController.php:19
 * @route '/intermediate/applications/{applicationId}/interviews'
 */
export const index = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/intermediate/applications/{applicationId}/interviews',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::index
 * @see app/Http/Controllers/IntermediateInterviewerController.php:19
 * @route '/intermediate/applications/{applicationId}/interviews'
 */
index.url = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return index.definition.url
            .replace('{applicationId}', parsedArgs.applicationId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::index
 * @see app/Http/Controllers/IntermediateInterviewerController.php:19
 * @route '/intermediate/applications/{applicationId}/interviews'
 */
index.get = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::index
 * @see app/Http/Controllers/IntermediateInterviewerController.php:19
 * @route '/intermediate/applications/{applicationId}/interviews'
 */
index.head = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateInterviewerController::index
 * @see app/Http/Controllers/IntermediateInterviewerController.php:19
 * @route '/intermediate/applications/{applicationId}/interviews'
 */
    const indexForm = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::index
 * @see app/Http/Controllers/IntermediateInterviewerController.php:19
 * @route '/intermediate/applications/{applicationId}/interviews'
 */
        indexForm.get = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::index
 * @see app/Http/Controllers/IntermediateInterviewerController.php:19
 * @route '/intermediate/applications/{applicationId}/interviews'
 */
        indexForm.head = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::available
 * @see app/Http/Controllers/IntermediateInterviewerController.php:45
 * @route '/intermediate/interviewers/available'
 */
export const available = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: available.url(options),
    method: 'get',
})

available.definition = {
    methods: ["get","head"],
    url: '/intermediate/interviewers/available',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::available
 * @see app/Http/Controllers/IntermediateInterviewerController.php:45
 * @route '/intermediate/interviewers/available'
 */
available.url = (options?: RouteQueryOptions) => {
    return available.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::available
 * @see app/Http/Controllers/IntermediateInterviewerController.php:45
 * @route '/intermediate/interviewers/available'
 */
available.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: available.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::available
 * @see app/Http/Controllers/IntermediateInterviewerController.php:45
 * @route '/intermediate/interviewers/available'
 */
available.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: available.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateInterviewerController::available
 * @see app/Http/Controllers/IntermediateInterviewerController.php:45
 * @route '/intermediate/interviewers/available'
 */
    const availableForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: available.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::available
 * @see app/Http/Controllers/IntermediateInterviewerController.php:45
 * @route '/intermediate/interviewers/available'
 */
        availableForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: available.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::available
 * @see app/Http/Controllers/IntermediateInterviewerController.php:45
 * @route '/intermediate/interviewers/available'
 */
        availableForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: available.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    available.form = availableForm
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::bulkAdd
 * @see app/Http/Controllers/IntermediateInterviewerController.php:65
 * @route '/intermediate/applications/{applicationId}/interviews/bulk-add'
 */
export const bulkAdd = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkAdd.url(args, options),
    method: 'post',
})

bulkAdd.definition = {
    methods: ["post"],
    url: '/intermediate/applications/{applicationId}/interviews/bulk-add',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::bulkAdd
 * @see app/Http/Controllers/IntermediateInterviewerController.php:65
 * @route '/intermediate/applications/{applicationId}/interviews/bulk-add'
 */
bulkAdd.url = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return bulkAdd.definition.url
            .replace('{applicationId}', parsedArgs.applicationId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::bulkAdd
 * @see app/Http/Controllers/IntermediateInterviewerController.php:65
 * @route '/intermediate/applications/{applicationId}/interviews/bulk-add'
 */
bulkAdd.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkAdd.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateInterviewerController::bulkAdd
 * @see app/Http/Controllers/IntermediateInterviewerController.php:65
 * @route '/intermediate/applications/{applicationId}/interviews/bulk-add'
 */
    const bulkAddForm = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkAdd.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::bulkAdd
 * @see app/Http/Controllers/IntermediateInterviewerController.php:65
 * @route '/intermediate/applications/{applicationId}/interviews/bulk-add'
 */
        bulkAddForm.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkAdd.url(args, options),
            method: 'post',
        })
    
    bulkAdd.form = bulkAddForm
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::bulkUpdateSchedule
 * @see app/Http/Controllers/IntermediateInterviewerController.php:166
 * @route '/intermediate/applications/{applicationId}/interviews/bulk-update-schedule'
 */
export const bulkUpdateSchedule = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkUpdateSchedule.url(args, options),
    method: 'post',
})

bulkUpdateSchedule.definition = {
    methods: ["post"],
    url: '/intermediate/applications/{applicationId}/interviews/bulk-update-schedule',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::bulkUpdateSchedule
 * @see app/Http/Controllers/IntermediateInterviewerController.php:166
 * @route '/intermediate/applications/{applicationId}/interviews/bulk-update-schedule'
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
* @see \App\Http\Controllers\IntermediateInterviewerController::bulkUpdateSchedule
 * @see app/Http/Controllers/IntermediateInterviewerController.php:166
 * @route '/intermediate/applications/{applicationId}/interviews/bulk-update-schedule'
 */
bulkUpdateSchedule.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkUpdateSchedule.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateInterviewerController::bulkUpdateSchedule
 * @see app/Http/Controllers/IntermediateInterviewerController.php:166
 * @route '/intermediate/applications/{applicationId}/interviews/bulk-update-schedule'
 */
    const bulkUpdateScheduleForm = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkUpdateSchedule.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::bulkUpdateSchedule
 * @see app/Http/Controllers/IntermediateInterviewerController.php:166
 * @route '/intermediate/applications/{applicationId}/interviews/bulk-update-schedule'
 */
        bulkUpdateScheduleForm.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkUpdateSchedule.url(args, options),
            method: 'post',
        })
    
    bulkUpdateSchedule.form = bulkUpdateScheduleForm
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::decision
 * @see app/Http/Controllers/IntermediateInterviewerController.php:239
 * @route '/intermediate/applications/{applicationId}/interviews/{interviewId}/decision'
 */
export const decision = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: decision.url(args, options),
    method: 'post',
})

decision.definition = {
    methods: ["post"],
    url: '/intermediate/applications/{applicationId}/interviews/{interviewId}/decision',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::decision
 * @see app/Http/Controllers/IntermediateInterviewerController.php:239
 * @route '/intermediate/applications/{applicationId}/interviews/{interviewId}/decision'
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
* @see \App\Http\Controllers\IntermediateInterviewerController::decision
 * @see app/Http/Controllers/IntermediateInterviewerController.php:239
 * @route '/intermediate/applications/{applicationId}/interviews/{interviewId}/decision'
 */
decision.post = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: decision.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateInterviewerController::decision
 * @see app/Http/Controllers/IntermediateInterviewerController.php:239
 * @route '/intermediate/applications/{applicationId}/interviews/{interviewId}/decision'
 */
    const decisionForm = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: decision.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::decision
 * @see app/Http/Controllers/IntermediateInterviewerController.php:239
 * @route '/intermediate/applications/{applicationId}/interviews/{interviewId}/decision'
 */
        decisionForm.post = (args: { applicationId: string | number, interviewId: string | number } | [applicationId: string | number, interviewId: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: decision.url(args, options),
            method: 'post',
        })
    
    decision.form = decisionForm
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::initialAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:329
 * @route '/intermediate/applications/{applicationId}/initial-assignments'
 */
export const initialAssignments = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: initialAssignments.url(args, options),
    method: 'get',
})

initialAssignments.definition = {
    methods: ["get","head"],
    url: '/intermediate/applications/{applicationId}/initial-assignments',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::initialAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:329
 * @route '/intermediate/applications/{applicationId}/initial-assignments'
 */
initialAssignments.url = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return initialAssignments.definition.url
            .replace('{applicationId}', parsedArgs.applicationId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::initialAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:329
 * @route '/intermediate/applications/{applicationId}/initial-assignments'
 */
initialAssignments.get = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: initialAssignments.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::initialAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:329
 * @route '/intermediate/applications/{applicationId}/initial-assignments'
 */
initialAssignments.head = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: initialAssignments.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateInterviewerController::initialAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:329
 * @route '/intermediate/applications/{applicationId}/initial-assignments'
 */
    const initialAssignmentsForm = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: initialAssignments.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::initialAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:329
 * @route '/intermediate/applications/{applicationId}/initial-assignments'
 */
        initialAssignmentsForm.get = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: initialAssignments.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::initialAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:329
 * @route '/intermediate/applications/{applicationId}/initial-assignments'
 */
        initialAssignmentsForm.head = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: initialAssignments.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    initialAssignments.form = initialAssignmentsForm
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::finalAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:337
 * @route '/intermediate/applications/{applicationId}/final-assignments'
 */
export const finalAssignments = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: finalAssignments.url(args, options),
    method: 'get',
})

finalAssignments.definition = {
    methods: ["get","head"],
    url: '/intermediate/applications/{applicationId}/final-assignments',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::finalAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:337
 * @route '/intermediate/applications/{applicationId}/final-assignments'
 */
finalAssignments.url = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return finalAssignments.definition.url
            .replace('{applicationId}', parsedArgs.applicationId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::finalAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:337
 * @route '/intermediate/applications/{applicationId}/final-assignments'
 */
finalAssignments.get = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: finalAssignments.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::finalAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:337
 * @route '/intermediate/applications/{applicationId}/final-assignments'
 */
finalAssignments.head = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: finalAssignments.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateInterviewerController::finalAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:337
 * @route '/intermediate/applications/{applicationId}/final-assignments'
 */
    const finalAssignmentsForm = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: finalAssignments.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::finalAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:337
 * @route '/intermediate/applications/{applicationId}/final-assignments'
 */
        finalAssignmentsForm.get = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: finalAssignments.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::finalAssignments
 * @see app/Http/Controllers/IntermediateInterviewerController.php:337
 * @route '/intermediate/applications/{applicationId}/final-assignments'
 */
        finalAssignmentsForm.head = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: finalAssignments.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    finalAssignments.form = finalAssignmentsForm
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::hasMixedResults
 * @see app/Http/Controllers/IntermediateInterviewerController.php:345
 * @route '/intermediate/applications/{applicationId}/has-mixed-results/{type}'
 */
export const hasMixedResults = (args: { applicationId: string | number, type: string | number } | [applicationId: string | number, type: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: hasMixedResults.url(args, options),
    method: 'get',
})

hasMixedResults.definition = {
    methods: ["get","head"],
    url: '/intermediate/applications/{applicationId}/has-mixed-results/{type}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::hasMixedResults
 * @see app/Http/Controllers/IntermediateInterviewerController.php:345
 * @route '/intermediate/applications/{applicationId}/has-mixed-results/{type}'
 */
hasMixedResults.url = (args: { applicationId: string | number, type: string | number } | [applicationId: string | number, type: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    applicationId: args[0],
                    type: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicationId: args.applicationId,
                                type: args.type,
                }

    return hasMixedResults.definition.url
            .replace('{applicationId}', parsedArgs.applicationId.toString())
            .replace('{type}', parsedArgs.type.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::hasMixedResults
 * @see app/Http/Controllers/IntermediateInterviewerController.php:345
 * @route '/intermediate/applications/{applicationId}/has-mixed-results/{type}'
 */
hasMixedResults.get = (args: { applicationId: string | number, type: string | number } | [applicationId: string | number, type: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: hasMixedResults.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::hasMixedResults
 * @see app/Http/Controllers/IntermediateInterviewerController.php:345
 * @route '/intermediate/applications/{applicationId}/has-mixed-results/{type}'
 */
hasMixedResults.head = (args: { applicationId: string | number, type: string | number } | [applicationId: string | number, type: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: hasMixedResults.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateInterviewerController::hasMixedResults
 * @see app/Http/Controllers/IntermediateInterviewerController.php:345
 * @route '/intermediate/applications/{applicationId}/has-mixed-results/{type}'
 */
    const hasMixedResultsForm = (args: { applicationId: string | number, type: string | number } | [applicationId: string | number, type: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: hasMixedResults.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::hasMixedResults
 * @see app/Http/Controllers/IntermediateInterviewerController.php:345
 * @route '/intermediate/applications/{applicationId}/has-mixed-results/{type}'
 */
        hasMixedResultsForm.get = (args: { applicationId: string | number, type: string | number } | [applicationId: string | number, type: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: hasMixedResults.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::hasMixedResults
 * @see app/Http/Controllers/IntermediateInterviewerController.php:345
 * @route '/intermediate/applications/{applicationId}/has-mixed-results/{type}'
 */
        hasMixedResultsForm.head = (args: { applicationId: string | number, type: string | number } | [applicationId: string | number, type: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: hasMixedResults.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    hasMixedResults.form = hasMixedResultsForm
/**
* @see \App\Http\Controllers\IntermediateInterviewerController::stageUpdateSchedule
 * @see app/Http/Controllers/IntermediateInterviewerController.php:456
 * @route '/intermediate/applications/{applicationId}/interviews/stage-update-schedule'
 */
export const stageUpdateSchedule = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: stageUpdateSchedule.url(args, options),
    method: 'post',
})

stageUpdateSchedule.definition = {
    methods: ["post"],
    url: '/intermediate/applications/{applicationId}/interviews/stage-update-schedule',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::stageUpdateSchedule
 * @see app/Http/Controllers/IntermediateInterviewerController.php:456
 * @route '/intermediate/applications/{applicationId}/interviews/stage-update-schedule'
 */
stageUpdateSchedule.url = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return stageUpdateSchedule.definition.url
            .replace('{applicationId}', parsedArgs.applicationId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateInterviewerController::stageUpdateSchedule
 * @see app/Http/Controllers/IntermediateInterviewerController.php:456
 * @route '/intermediate/applications/{applicationId}/interviews/stage-update-schedule'
 */
stageUpdateSchedule.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: stageUpdateSchedule.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateInterviewerController::stageUpdateSchedule
 * @see app/Http/Controllers/IntermediateInterviewerController.php:456
 * @route '/intermediate/applications/{applicationId}/interviews/stage-update-schedule'
 */
    const stageUpdateScheduleForm = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: stageUpdateSchedule.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateInterviewerController::stageUpdateSchedule
 * @see app/Http/Controllers/IntermediateInterviewerController.php:456
 * @route '/intermediate/applications/{applicationId}/interviews/stage-update-schedule'
 */
        stageUpdateScheduleForm.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: stageUpdateSchedule.url(args, options),
            method: 'post',
        })
    
    stageUpdateSchedule.form = stageUpdateScheduleForm
const IntermediateInterviewerController = { index, available, bulkAdd, bulkUpdateSchedule, decision, initialAssignments, finalAssignments, hasMixedResults, stageUpdateSchedule }

export default IntermediateInterviewerController