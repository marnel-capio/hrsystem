import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/applicant-applications'
 */
const index3cd56028c4738f0b4d6738833cc85f37 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index3cd56028c4738f0b4d6738833cc85f37.url(options),
    method: 'get',
})

index3cd56028c4738f0b4d6738833cc85f37.definition = {
    methods: ["get","head"],
    url: '/applicant-applications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/applicant-applications'
 */
index3cd56028c4738f0b4d6738833cc85f37.url = (options?: RouteQueryOptions) => {
    return index3cd56028c4738f0b4d6738833cc85f37.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/applicant-applications'
 */
index3cd56028c4738f0b4d6738833cc85f37.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index3cd56028c4738f0b4d6738833cc85f37.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/applicant-applications'
 */
index3cd56028c4738f0b4d6738833cc85f37.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index3cd56028c4738f0b4d6738833cc85f37.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/applicant-applications'
 */
    const index3cd56028c4738f0b4d6738833cc85f37Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index3cd56028c4738f0b4d6738833cc85f37.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/applicant-applications'
 */
        index3cd56028c4738f0b4d6738833cc85f37Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index3cd56028c4738f0b4d6738833cc85f37.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/applicant-applications'
 */
        index3cd56028c4738f0b4d6738833cc85f37Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index3cd56028c4738f0b4d6738833cc85f37.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index3cd56028c4738f0b4d6738833cc85f37.form = index3cd56028c4738f0b4d6738833cc85f37Form
    /**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications'
 */
const index9ce877a72747144a9d75602d24f5c705 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index9ce877a72747144a9d75602d24f5c705.url(options),
    method: 'get',
})

index9ce877a72747144a9d75602d24f5c705.definition = {
    methods: ["get","head"],
    url: '/action/applications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications'
 */
index9ce877a72747144a9d75602d24f5c705.url = (options?: RouteQueryOptions) => {
    return index9ce877a72747144a9d75602d24f5c705.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications'
 */
index9ce877a72747144a9d75602d24f5c705.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index9ce877a72747144a9d75602d24f5c705.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications'
 */
index9ce877a72747144a9d75602d24f5c705.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index9ce877a72747144a9d75602d24f5c705.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications'
 */
    const index9ce877a72747144a9d75602d24f5c705Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index9ce877a72747144a9d75602d24f5c705.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications'
 */
        index9ce877a72747144a9d75602d24f5c705Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index9ce877a72747144a9d75602d24f5c705.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::index
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications'
 */
        index9ce877a72747144a9d75602d24f5c705Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index9ce877a72747144a9d75602d24f5c705.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index9ce877a72747144a9d75602d24f5c705.form = index9ce877a72747144a9d75602d24f5c705Form

export const index = {
    '/applicant-applications': index3cd56028c4738f0b4d6738833cc85f37,
    '/action/applications': index9ce877a72747144a9d75602d24f5c705,
}

/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/applicant-applications/create'
 */
const createcb294fac78ec4bfac63fdc028bd134cb = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createcb294fac78ec4bfac63fdc028bd134cb.url(options),
    method: 'get',
})

createcb294fac78ec4bfac63fdc028bd134cb.definition = {
    methods: ["get","head"],
    url: '/applicant-applications/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/applicant-applications/create'
 */
createcb294fac78ec4bfac63fdc028bd134cb.url = (options?: RouteQueryOptions) => {
    return createcb294fac78ec4bfac63fdc028bd134cb.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/applicant-applications/create'
 */
createcb294fac78ec4bfac63fdc028bd134cb.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createcb294fac78ec4bfac63fdc028bd134cb.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/applicant-applications/create'
 */
createcb294fac78ec4bfac63fdc028bd134cb.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: createcb294fac78ec4bfac63fdc028bd134cb.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/applicant-applications/create'
 */
    const createcb294fac78ec4bfac63fdc028bd134cbForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: createcb294fac78ec4bfac63fdc028bd134cb.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/applicant-applications/create'
 */
        createcb294fac78ec4bfac63fdc028bd134cbForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createcb294fac78ec4bfac63fdc028bd134cb.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/applicant-applications/create'
 */
        createcb294fac78ec4bfac63fdc028bd134cbForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createcb294fac78ec4bfac63fdc028bd134cb.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    createcb294fac78ec4bfac63fdc028bd134cb.form = createcb294fac78ec4bfac63fdc028bd134cbForm
    /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/action/applications/register'
 */
const createce0773631ba011be5f08c8d41c5e9559 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createce0773631ba011be5f08c8d41c5e9559.url(options),
    method: 'get',
})

createce0773631ba011be5f08c8d41c5e9559.definition = {
    methods: ["get","head"],
    url: '/action/applications/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/action/applications/register'
 */
createce0773631ba011be5f08c8d41c5e9559.url = (options?: RouteQueryOptions) => {
    return createce0773631ba011be5f08c8d41c5e9559.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/action/applications/register'
 */
createce0773631ba011be5f08c8d41c5e9559.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createce0773631ba011be5f08c8d41c5e9559.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/action/applications/register'
 */
createce0773631ba011be5f08c8d41c5e9559.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: createce0773631ba011be5f08c8d41c5e9559.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/action/applications/register'
 */
    const createce0773631ba011be5f08c8d41c5e9559Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: createce0773631ba011be5f08c8d41c5e9559.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/action/applications/register'
 */
        createce0773631ba011be5f08c8d41c5e9559Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createce0773631ba011be5f08c8d41c5e9559.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:21
 * @route '/action/applications/register'
 */
        createce0773631ba011be5f08c8d41c5e9559Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createce0773631ba011be5f08c8d41c5e9559.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    createce0773631ba011be5f08c8d41c5e9559.form = createce0773631ba011be5f08c8d41c5e9559Form

export const create = {
    '/applicant-applications/create': createcb294fac78ec4bfac63fdc028bd134cb,
    '/action/applications/register': createce0773631ba011be5f08c8d41c5e9559,
}

/**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/applicant-applications'
 */
const store3cd56028c4738f0b4d6738833cc85f37 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store3cd56028c4738f0b4d6738833cc85f37.url(options),
    method: 'post',
})

store3cd56028c4738f0b4d6738833cc85f37.definition = {
    methods: ["post"],
    url: '/applicant-applications',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/applicant-applications'
 */
store3cd56028c4738f0b4d6738833cc85f37.url = (options?: RouteQueryOptions) => {
    return store3cd56028c4738f0b4d6738833cc85f37.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/applicant-applications'
 */
store3cd56028c4738f0b4d6738833cc85f37.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store3cd56028c4738f0b4d6738833cc85f37.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/applicant-applications'
 */
    const store3cd56028c4738f0b4d6738833cc85f37Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store3cd56028c4738f0b4d6738833cc85f37.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/applicant-applications'
 */
        store3cd56028c4738f0b4d6738833cc85f37Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store3cd56028c4738f0b4d6738833cc85f37.url(options),
            method: 'post',
        })
    
    store3cd56028c4738f0b4d6738833cc85f37.form = store3cd56028c4738f0b4d6738833cc85f37Form
    /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/action/applications'
 */
const store9ce877a72747144a9d75602d24f5c705 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store9ce877a72747144a9d75602d24f5c705.url(options),
    method: 'post',
})

store9ce877a72747144a9d75602d24f5c705.definition = {
    methods: ["post"],
    url: '/action/applications',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/action/applications'
 */
store9ce877a72747144a9d75602d24f5c705.url = (options?: RouteQueryOptions) => {
    return store9ce877a72747144a9d75602d24f5c705.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/action/applications'
 */
store9ce877a72747144a9d75602d24f5c705.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store9ce877a72747144a9d75602d24f5c705.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/action/applications'
 */
    const store9ce877a72747144a9d75602d24f5c705Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store9ce877a72747144a9d75602d24f5c705.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:37
 * @route '/action/applications'
 */
        store9ce877a72747144a9d75602d24f5c705Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store9ce877a72747144a9d75602d24f5c705.url(options),
            method: 'post',
        })
    
    store9ce877a72747144a9d75602d24f5c705.form = store9ce877a72747144a9d75602d24f5c705Form

export const store = {
    '/applicant-applications': store3cd56028c4738f0b4d6738833cc85f37,
    '/action/applications': store9ce877a72747144a9d75602d24f5c705,
}

/**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/applicant-applications/{id}'
 */
const showdd3ed03cf65e040ef0d900001fba1366 = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showdd3ed03cf65e040ef0d900001fba1366.url(args, options),
    method: 'get',
})

showdd3ed03cf65e040ef0d900001fba1366.definition = {
    methods: ["get","head"],
    url: '/applicant-applications/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/applicant-applications/{id}'
 */
showdd3ed03cf65e040ef0d900001fba1366.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return showdd3ed03cf65e040ef0d900001fba1366.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/applicant-applications/{id}'
 */
showdd3ed03cf65e040ef0d900001fba1366.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showdd3ed03cf65e040ef0d900001fba1366.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/applicant-applications/{id}'
 */
showdd3ed03cf65e040ef0d900001fba1366.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: showdd3ed03cf65e040ef0d900001fba1366.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/applicant-applications/{id}'
 */
    const showdd3ed03cf65e040ef0d900001fba1366Form = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: showdd3ed03cf65e040ef0d900001fba1366.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/applicant-applications/{id}'
 */
        showdd3ed03cf65e040ef0d900001fba1366Form.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: showdd3ed03cf65e040ef0d900001fba1366.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/applicant-applications/{id}'
 */
        showdd3ed03cf65e040ef0d900001fba1366Form.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: showdd3ed03cf65e040ef0d900001fba1366.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    showdd3ed03cf65e040ef0d900001fba1366.form = showdd3ed03cf65e040ef0d900001fba1366Form
    /**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/action/applications/{id}'
 */
const showc3ad6b3b435c15606ae7b57cb12445ea = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showc3ad6b3b435c15606ae7b57cb12445ea.url(args, options),
    method: 'get',
})

showc3ad6b3b435c15606ae7b57cb12445ea.definition = {
    methods: ["get","head"],
    url: '/action/applications/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/action/applications/{id}'
 */
showc3ad6b3b435c15606ae7b57cb12445ea.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return showc3ad6b3b435c15606ae7b57cb12445ea.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/action/applications/{id}'
 */
showc3ad6b3b435c15606ae7b57cb12445ea.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showc3ad6b3b435c15606ae7b57cb12445ea.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/action/applications/{id}'
 */
showc3ad6b3b435c15606ae7b57cb12445ea.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: showc3ad6b3b435c15606ae7b57cb12445ea.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/action/applications/{id}'
 */
    const showc3ad6b3b435c15606ae7b57cb12445eaForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: showc3ad6b3b435c15606ae7b57cb12445ea.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/action/applications/{id}'
 */
        showc3ad6b3b435c15606ae7b57cb12445eaForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: showc3ad6b3b435c15606ae7b57cb12445ea.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::show
 * @see app/Http/Controllers/ActionApplicationController.php:88
 * @route '/action/applications/{id}'
 */
        showc3ad6b3b435c15606ae7b57cb12445eaForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: showc3ad6b3b435c15606ae7b57cb12445ea.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    showc3ad6b3b435c15606ae7b57cb12445ea.form = showc3ad6b3b435c15606ae7b57cb12445eaForm

export const show = {
    '/applicant-applications/{id}': showdd3ed03cf65e040ef0d900001fba1366,
    '/action/applications/{id}': showc3ad6b3b435c15606ae7b57cb12445ea,
}

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
/**
* @see \App\Http\Controllers\ActionApplicationController::getStatusHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/history'
 */
export const getStatusHistory = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getStatusHistory.url(args, options),
    method: 'get',
})

getStatusHistory.definition = {
    methods: ["get","head"],
    url: '/action/applications/{id}/history',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::getStatusHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/history'
 */
getStatusHistory.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return getStatusHistory.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::getStatusHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/history'
 */
getStatusHistory.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getStatusHistory.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::getStatusHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/history'
 */
getStatusHistory.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getStatusHistory.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::getStatusHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/history'
 */
    const getStatusHistoryForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getStatusHistory.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::getStatusHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/history'
 */
        getStatusHistoryForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getStatusHistory.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::getStatusHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/history'
 */
        getStatusHistoryForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getStatusHistory.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getStatusHistory.form = getStatusHistoryForm
/**
* @see \App\Http\Controllers\ActionApplicationController::getEmailHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
export const getEmailHistory = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getEmailHistory.url(args, options),
    method: 'get',
})

getEmailHistory.definition = {
    methods: ["get","head"],
    url: '/action/applications/{id}/emails',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::getEmailHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
getEmailHistory.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return getEmailHistory.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::getEmailHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
getEmailHistory.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getEmailHistory.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::getEmailHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
getEmailHistory.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getEmailHistory.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::getEmailHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
    const getEmailHistoryForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getEmailHistory.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::getEmailHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
        getEmailHistoryForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getEmailHistory.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::getEmailHistory
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
        getEmailHistoryForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getEmailHistory.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getEmailHistory.form = getEmailHistoryForm
/**
* @see \App\Http\Controllers\ActionApplicationController::sendNotification
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/notify'
 */
export const sendNotification = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendNotification.url(args, options),
    method: 'post',
})

sendNotification.definition = {
    methods: ["post"],
    url: '/action/applications/{id}/notify',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::sendNotification
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/notify'
 */
sendNotification.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return sendNotification.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::sendNotification
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/notify'
 */
sendNotification.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendNotification.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::sendNotification
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/notify'
 */
    const sendNotificationForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: sendNotification.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::sendNotification
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/notify'
 */
        sendNotificationForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: sendNotification.url(args, options),
            method: 'post',
        })
    
    sendNotification.form = sendNotificationForm
/**
* @see \App\Http\Controllers\ActionApplicationController::downloadApplication
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
export const downloadApplication = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadApplication.url(args, options),
    method: 'get',
})

downloadApplication.definition = {
    methods: ["get","head"],
    url: '/action/applications/{id}/download',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::downloadApplication
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
downloadApplication.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return downloadApplication.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::downloadApplication
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
downloadApplication.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadApplication.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::downloadApplication
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
downloadApplication.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadApplication.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::downloadApplication
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
    const downloadApplicationForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: downloadApplication.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::downloadApplication
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
        downloadApplicationForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: downloadApplication.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::downloadApplication
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
        downloadApplicationForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: downloadApplication.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    downloadApplication.form = downloadApplicationForm
/**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
export const getApplicantsForBatch = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getApplicantsForBatch.url(args, options),
    method: 'get',
})

getApplicantsForBatch.definition = {
    methods: ["get","head"],
    url: '/action/applications/eligible-applicants/{batchId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
getApplicantsForBatch.url = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { batchId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    batchId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        batchId: args.batchId,
                }

    return getApplicantsForBatch.definition.url
            .replace('{batchId}', parsedArgs.batchId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
getApplicantsForBatch.get = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getApplicantsForBatch.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
getApplicantsForBatch.head = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getApplicantsForBatch.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
    const getApplicantsForBatchForm = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getApplicantsForBatch.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
        getApplicantsForBatchForm.get = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getApplicantsForBatch.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::getApplicantsForBatch
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
        getApplicantsForBatchForm.head = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getApplicantsForBatch.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getApplicantsForBatch.form = getApplicantsForBatchForm
/**
* @see \App\Http\Controllers\ActionApplicationController::checkEligibility
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
export const checkEligibility = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkEligibility.url(options),
    method: 'post',
})

checkEligibility.definition = {
    methods: ["post"],
    url: '/action/applications/check-eligibility',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::checkEligibility
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
checkEligibility.url = (options?: RouteQueryOptions) => {
    return checkEligibility.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::checkEligibility
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
checkEligibility.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkEligibility.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::checkEligibility
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
    const checkEligibilityForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkEligibility.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::checkEligibility
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
        checkEligibilityForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkEligibility.url(options),
            method: 'post',
        })
    
    checkEligibility.form = checkEligibilityForm
/**
* @see \App\Http\Controllers\ActionApplicationController::getInterviewers
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
export const getInterviewers = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getInterviewers.url(options),
    method: 'get',
})

getInterviewers.definition = {
    methods: ["get","head"],
    url: '/action/applications/users/interviewers',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::getInterviewers
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
getInterviewers.url = (options?: RouteQueryOptions) => {
    return getInterviewers.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::getInterviewers
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
getInterviewers.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getInterviewers.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::getInterviewers
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
getInterviewers.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getInterviewers.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::getInterviewers
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
    const getInterviewersForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getInterviewers.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::getInterviewers
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
        getInterviewersForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getInterviewers.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::getInterviewers
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
        getInterviewersForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getInterviewers.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getInterviewers.form = getInterviewersForm
/**
* @see \App\Http\Controllers\ActionApplicationController::getPreviousApplications
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
export const getPreviousApplications = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getPreviousApplications.url(args, options),
    method: 'get',
})

getPreviousApplications.definition = {
    methods: ["get","head"],
    url: '/action/applications/previous/{applicantId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::getPreviousApplications
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
getPreviousApplications.url = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { applicantId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    applicantId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicantId: args.applicantId,
                }

    return getPreviousApplications.definition.url
            .replace('{applicantId}', parsedArgs.applicantId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::getPreviousApplications
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
getPreviousApplications.get = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getPreviousApplications.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::getPreviousApplications
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
getPreviousApplications.head = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getPreviousApplications.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::getPreviousApplications
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
    const getPreviousApplicationsForm = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: getPreviousApplications.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::getPreviousApplications
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
        getPreviousApplicationsForm.get = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getPreviousApplications.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::getPreviousApplications
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
        getPreviousApplicationsForm.head = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: getPreviousApplications.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    getPreviousApplications.form = getPreviousApplicationsForm
/**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/edit'
 */
export const edit = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/action/applications/{id}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/edit'
 */
edit.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/edit'
 */
edit.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/edit'
 */
edit.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/edit'
 */
    const editForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/edit'
 */
        editForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::edit
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/edit'
 */
        editForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\ActionApplicationController::update
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/action/applications/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::update
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}'
 */
update.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::update
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::update
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}'
 */
    const updateForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::update
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}'
 */
        updateForm.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
const ActionApplicationController = { index, create, store, show, checkUnique, getStatusHistory, getEmailHistory, sendNotification, downloadApplication, getApplicantsForBatch, checkEligibility, getInterviewers, getPreviousApplications, edit, update }

export default ActionApplicationController