import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action/batches/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:14
 * @route '/action/batches/create'
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
const ActionBatchController = { index, store }

export default ActionBatchController