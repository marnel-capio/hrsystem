import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
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
const batches = {
    list: Object.assign(list, list),
}

export default batches