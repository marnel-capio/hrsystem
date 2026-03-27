import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
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
 * @see app/Http/Controllers/ActionApplicationController.php:32
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
 * @see app/Http/Controllers/ActionApplicationController.php:32
 * @route '/applicant-applications/create'
 */
createcb294fac78ec4bfac63fdc028bd134cb.url = (options?: RouteQueryOptions) => {
    return createcb294fac78ec4bfac63fdc028bd134cb.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:32
 * @route '/applicant-applications/create'
 */
createcb294fac78ec4bfac63fdc028bd134cb.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createcb294fac78ec4bfac63fdc028bd134cb.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:32
 * @route '/applicant-applications/create'
 */
createcb294fac78ec4bfac63fdc028bd134cb.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: createcb294fac78ec4bfac63fdc028bd134cb.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:32
 * @route '/applicant-applications/create'
 */
    const createcb294fac78ec4bfac63fdc028bd134cbForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: createcb294fac78ec4bfac63fdc028bd134cb.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:32
 * @route '/applicant-applications/create'
 */
        createcb294fac78ec4bfac63fdc028bd134cbForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createcb294fac78ec4bfac63fdc028bd134cb.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:32
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
 * @see app/Http/Controllers/ActionApplicationController.php:32
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
 * @see app/Http/Controllers/ActionApplicationController.php:32
 * @route '/action/applications/register'
 */
createce0773631ba011be5f08c8d41c5e9559.url = (options?: RouteQueryOptions) => {
    return createce0773631ba011be5f08c8d41c5e9559.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:32
 * @route '/action/applications/register'
 */
createce0773631ba011be5f08c8d41c5e9559.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: createce0773631ba011be5f08c8d41c5e9559.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:32
 * @route '/action/applications/register'
 */
createce0773631ba011be5f08c8d41c5e9559.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: createce0773631ba011be5f08c8d41c5e9559.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:32
 * @route '/action/applications/register'
 */
    const createce0773631ba011be5f08c8d41c5e9559Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: createce0773631ba011be5f08c8d41c5e9559.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:32
 * @route '/action/applications/register'
 */
        createce0773631ba011be5f08c8d41c5e9559Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: createce0773631ba011be5f08c8d41c5e9559.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::create
 * @see app/Http/Controllers/ActionApplicationController.php:32
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
 * @see app/Http/Controllers/ActionApplicationController.php:42
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
 * @see app/Http/Controllers/ActionApplicationController.php:42
 * @route '/applicant-applications'
 */
store3cd56028c4738f0b4d6738833cc85f37.url = (options?: RouteQueryOptions) => {
    return store3cd56028c4738f0b4d6738833cc85f37.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:42
 * @route '/applicant-applications'
 */
store3cd56028c4738f0b4d6738833cc85f37.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store3cd56028c4738f0b4d6738833cc85f37.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:42
 * @route '/applicant-applications'
 */
    const store3cd56028c4738f0b4d6738833cc85f37Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store3cd56028c4738f0b4d6738833cc85f37.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:42
 * @route '/applicant-applications'
 */
        store3cd56028c4738f0b4d6738833cc85f37Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store3cd56028c4738f0b4d6738833cc85f37.url(options),
            method: 'post',
        })
    
    store3cd56028c4738f0b4d6738833cc85f37.form = store3cd56028c4738f0b4d6738833cc85f37Form
    /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:42
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
 * @see app/Http/Controllers/ActionApplicationController.php:42
 * @route '/action/applications'
 */
store9ce877a72747144a9d75602d24f5c705.url = (options?: RouteQueryOptions) => {
    return store9ce877a72747144a9d75602d24f5c705.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:42
 * @route '/action/applications'
 */
store9ce877a72747144a9d75602d24f5c705.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store9ce877a72747144a9d75602d24f5c705.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:42
 * @route '/action/applications'
 */
    const store9ce877a72747144a9d75602d24f5c705Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store9ce877a72747144a9d75602d24f5c705.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::store
 * @see app/Http/Controllers/ActionApplicationController.php:42
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
 * @see app/Http/Controllers/ActionApplicationController.php:76
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
 * @see app/Http/Controllers/ActionApplicationController.php:76
 * @route '/applicant-applications/check-unique'
 */
checkUnique.url = (options?: RouteQueryOptions) => {
    return checkUnique.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::checkUnique
 * @see app/Http/Controllers/ActionApplicationController.php:76
 * @route '/applicant-applications/check-unique'
 */
checkUnique.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkUnique.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::checkUnique
 * @see app/Http/Controllers/ActionApplicationController.php:76
 * @route '/applicant-applications/check-unique'
 */
    const checkUniqueForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkUnique.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::checkUnique
 * @see app/Http/Controllers/ActionApplicationController.php:76
 * @route '/applicant-applications/check-unique'
 */
        checkUniqueForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkUnique.url(options),
            method: 'post',
        })
    
    checkUnique.form = checkUniqueForm
const ActionApplicationController = { index, create, store, show, checkUnique }

export default ActionApplicationController