import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:19
 * @route '/applicant-applications'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/applicant-applications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:19
 * @route '/applicant-applications'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:19
 * @route '/applicant-applications'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:19
 * @route '/applicant-applications'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:19
 * @route '/applicant-applications'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:19
 * @route '/applicant-applications'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:19
 * @route '/applicant-applications'
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
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/applicant-applications/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/applicant-applications/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/applicant-applications/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/applicant-applications/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/applicant-applications/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/applicant-applications/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/applicant-applications/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/applicant-applications/create'
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
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:55
 * @route '/applicant-applications'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/applicant-applications',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:55
 * @route '/applicant-applications'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:55
 * @route '/applicant-applications'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:55
 * @route '/applicant-applications'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:55
 * @route '/applicant-applications'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\ActionApplicationController::detail
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/applicant-applications/{id}'
 */
export const detail = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detail.url(args, options),
    method: 'get',
})

detail.definition = {
    methods: ["get","head"],
    url: '/applicant-applications/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::detail
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/applicant-applications/{id}'
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
* @see \App\Http\Controllers\ActionApplicationController::detail
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/applicant-applications/{id}'
 */
detail.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detail.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::detail
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/applicant-applications/{id}'
 */
detail.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: detail.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::detail
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/applicant-applications/{id}'
 */
    const detailForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: detail.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::detail
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/applicant-applications/{id}'
 */
        detailForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: detail.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::detail
 * @see app/Http/Controllers/ActionApplicationController.php:107
 * @route '/applicant-applications/{id}'
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
/**
* @see \App\Http\Controllers\ActionApplicationController::checkUnique
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/applicant-applications/check-unique'
 */
export const checkUnique = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkUnique.url(options),
    method: 'post',
})

checkUnique.definition = {
    methods: ["post"],
    url: '/applicant-applications/check-unique',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::checkUnique
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/applicant-applications/check-unique'
 */
checkUnique.url = (options?: RouteQueryOptions) => {
    return checkUnique.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::checkUnique
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/applicant-applications/check-unique'
 */
checkUnique.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkUnique.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::checkUnique
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/applicant-applications/check-unique'
 */
    const checkUniqueForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkUnique.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::checkUnique
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/applicant-applications/check-unique'
 */
        checkUniqueForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkUnique.url(options),
            method: 'post',
        })
    
    checkUnique.form = checkUniqueForm
const applicantApplications = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
detail: Object.assign(detail, detail),
checkUnique: Object.assign(checkUnique, checkUnique),
}

export default applicantApplications