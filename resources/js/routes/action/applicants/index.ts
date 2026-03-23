import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:23
 * @route '/action/applicants'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action/applicants',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:23
 * @route '/action/applicants'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:23
 * @route '/action/applicants'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:23
 * @route '/action/applicants'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:23
 * @route '/action/applicants'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:23
 * @route '/action/applicants'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:23
 * @route '/action/applicants'
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
/**
* @see \App\Http\Controllers\ActionApplicantController::register
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants/register'
 */
export const register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

register.definition = {
    methods: ["get","head"],
    url: '/action/applicants/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::register
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants/register'
 */
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantController::register
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants/register'
 */
register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicantController::register
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants/register'
 */
register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: register.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::register
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants/register'
 */
    const registerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: register.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::register
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants/register'
 */
        registerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: register.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicantController::register
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants/register'
 */
        registerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: register.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    register.form = registerForm
/**
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:28
 * @route '/action/applicants'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/action/applicants',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:28
 * @route '/action/applicants'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:28
 * @route '/action/applicants'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:28
 * @route '/action/applicants'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:28
 * @route '/action/applicants'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\ActionApplicantController::detail
 * @see app/Http/Controllers/ActionApplicantController.php:82
 * @route '/action/applicants/{id}'
 */
export const detail = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detail.url(args, options),
    method: 'get',
})

detail.definition = {
    methods: ["get","head"],
    url: '/action/applicants/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::detail
 * @see app/Http/Controllers/ActionApplicantController.php:82
 * @route '/action/applicants/{id}'
 */
detail.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return detail.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantController::detail
 * @see app/Http/Controllers/ActionApplicantController.php:82
 * @route '/action/applicants/{id}'
 */
detail.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detail.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicantController::detail
 * @see app/Http/Controllers/ActionApplicantController.php:82
 * @route '/action/applicants/{id}'
 */
detail.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: detail.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::detail
 * @see app/Http/Controllers/ActionApplicantController.php:82
 * @route '/action/applicants/{id}'
 */
    const detailForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: detail.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::detail
 * @see app/Http/Controllers/ActionApplicantController.php:82
 * @route '/action/applicants/{id}'
 */
        detailForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: detail.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicantController::detail
 * @see app/Http/Controllers/ActionApplicantController.php:82
 * @route '/action/applicants/{id}'
 */
        detailForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: detail.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    detail.form = detailForm
const applicants = {
    index: Object.assign(index, index),
register: Object.assign(register, register),
store: Object.assign(store, store),
detail: Object.assign(detail, detail),
}

export default applicants