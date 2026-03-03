import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
export const list = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})

list.definition = {
    methods: ["get","head"],
    url: '/action/batches',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
list.url = (options?: RouteQueryOptions) => {
    return list.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
list.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
list.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: list.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
    const listForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: list.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
        listForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: list.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
        listForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: list.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    list.form = listForm
/**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/action/batches/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
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
* @see \App\Http\Controllers\ActionBatchController::detail
 * @see app/Http/Controllers/ActionBatchController.php:86
 * @route '/action/batches/{id})'
 */
export const detail = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detail.url(args, options),
    method: 'get',
})

detail.definition = {
    methods: ["get","head"],
    url: '/action/batches/{id})',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::detail
 * @see app/Http/Controllers/ActionBatchController.php:86
 * @route '/action/batches/{id})'
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
* @see \App\Http\Controllers\ActionBatchController::detail
 * @see app/Http/Controllers/ActionBatchController.php:86
 * @route '/action/batches/{id})'
 */
detail.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detail.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::detail
 * @see app/Http/Controllers/ActionBatchController.php:86
 * @route '/action/batches/{id})'
 */
detail.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: detail.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::detail
 * @see app/Http/Controllers/ActionBatchController.php:86
 * @route '/action/batches/{id})'
 */
    const detailForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: detail.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::detail
 * @see app/Http/Controllers/ActionBatchController.php:86
 * @route '/action/batches/{id})'
 */
        detailForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: detail.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::detail
 * @see app/Http/Controllers/ActionBatchController.php:86
 * @route '/action/batches/{id})'
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
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:48
 * @route '/action/batches/store'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/action/batches/store',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:48
 * @route '/action/batches/store'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:48
 * @route '/action/batches/store'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:48
 * @route '/action/batches/store'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:48
 * @route '/action/batches/store'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const batches = {
    list: Object.assign(list, list),
create: Object.assign(create, create),
detail: Object.assign(detail, detail),
store: Object.assign(store, store),
}

export default batches