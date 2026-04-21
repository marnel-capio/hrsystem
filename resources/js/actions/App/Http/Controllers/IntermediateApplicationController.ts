import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateApplicationController::create
 * @see app/Http/Controllers/IntermediateApplicationController.php:66
 * @route '/intermediate/applications/register'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/intermediate/applications/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::create
 * @see app/Http/Controllers/IntermediateApplicationController.php:66
 * @route '/intermediate/applications/register'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicationController::create
 * @see app/Http/Controllers/IntermediateApplicationController.php:66
 * @route '/intermediate/applications/register'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicationController::create
 * @see app/Http/Controllers/IntermediateApplicationController.php:66
 * @route '/intermediate/applications/register'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::create
 * @see app/Http/Controllers/IntermediateApplicationController.php:66
 * @route '/intermediate/applications/register'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::create
 * @see app/Http/Controllers/IntermediateApplicationController.php:66
 * @route '/intermediate/applications/register'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicationController::create
 * @see app/Http/Controllers/IntermediateApplicationController.php:66
 * @route '/intermediate/applications/register'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\IntermediateApplicationController::store
 * @see app/Http/Controllers/IntermediateApplicationController.php:102
 * @route '/intermediate/applications'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/intermediate/applications',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::store
 * @see app/Http/Controllers/IntermediateApplicationController.php:102
 * @route '/intermediate/applications'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicationController::store
 * @see app/Http/Controllers/IntermediateApplicationController.php:102
 * @route '/intermediate/applications'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::store
 * @see app/Http/Controllers/IntermediateApplicationController.php:102
 * @route '/intermediate/applications'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::store
 * @see app/Http/Controllers/IntermediateApplicationController.php:102
 * @route '/intermediate/applications'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\IntermediateApplicationController::eligibleApplicants
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications/eligible-applicants/{fy_week}'
 */
export const eligibleApplicants = (args: { fy_week: string | number } | [fy_week: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: eligibleApplicants.url(args, options),
    method: 'get',
})

eligibleApplicants.definition = {
    methods: ["get","head"],
    url: '/intermediate/applications/eligible-applicants/{fy_week}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::eligibleApplicants
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications/eligible-applicants/{fy_week}'
 */
eligibleApplicants.url = (args: { fy_week: string | number } | [fy_week: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { fy_week: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    fy_week: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        fy_week: args.fy_week,
                }

    return eligibleApplicants.definition.url
            .replace('{fy_week}', parsedArgs.fy_week.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicationController::eligibleApplicants
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications/eligible-applicants/{fy_week}'
 */
eligibleApplicants.get = (args: { fy_week: string | number } | [fy_week: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: eligibleApplicants.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicationController::eligibleApplicants
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications/eligible-applicants/{fy_week}'
 */
eligibleApplicants.head = (args: { fy_week: string | number } | [fy_week: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: eligibleApplicants.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::eligibleApplicants
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications/eligible-applicants/{fy_week}'
 */
    const eligibleApplicantsForm = (args: { fy_week: string | number } | [fy_week: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: eligibleApplicants.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::eligibleApplicants
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications/eligible-applicants/{fy_week}'
 */
        eligibleApplicantsForm.get = (args: { fy_week: string | number } | [fy_week: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: eligibleApplicants.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicationController::eligibleApplicants
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications/eligible-applicants/{fy_week}'
 */
        eligibleApplicantsForm.head = (args: { fy_week: string | number } | [fy_week: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: eligibleApplicants.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    eligibleApplicants.form = eligibleApplicantsForm
/**
* @see \App\Http\Controllers\IntermediateApplicationController::checkEligibility
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications/check-eligibility'
 */
export const checkEligibility = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkEligibility.url(options),
    method: 'post',
})

checkEligibility.definition = {
    methods: ["post"],
    url: '/intermediate/applications/check-eligibility',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::checkEligibility
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications/check-eligibility'
 */
checkEligibility.url = (options?: RouteQueryOptions) => {
    return checkEligibility.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicationController::checkEligibility
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications/check-eligibility'
 */
checkEligibility.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkEligibility.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::checkEligibility
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications/check-eligibility'
 */
    const checkEligibilityForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkEligibility.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::checkEligibility
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications/check-eligibility'
 */
        checkEligibilityForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkEligibility.url(options),
            method: 'post',
        })
    
    checkEligibility.form = checkEligibilityForm
/**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:21
 * @route '/intermediate/applications'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/intermediate/applications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:21
 * @route '/intermediate/applications'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:21
 * @route '/intermediate/applications'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:21
 * @route '/intermediate/applications'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:21
 * @route '/intermediate/applications'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:21
 * @route '/intermediate/applications'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:21
 * @route '/intermediate/applications'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
const IntermediateApplicationController = { create, store, eligibleApplicants, checkEligibility, index }

export default IntermediateApplicationController