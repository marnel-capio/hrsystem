import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/action/batches/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
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
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:32
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
 * @see app/Http/Controllers/ActionBatchController.php:32
 * @route '/action/batches/store'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:32
 * @route '/action/batches/store'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:32
 * @route '/action/batches/store'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:32
 * @route '/action/batches/store'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const action = {
    create: Object.assign(create, create),
store: Object.assign(store, store),
}

export default action