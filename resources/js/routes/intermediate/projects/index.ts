import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateProjectController::list
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
 */
export const list = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})

list.definition = {
    methods: ["get","head"],
    url: '/intermediate/projects',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateProjectController::list
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
 */
list.url = (options?: RouteQueryOptions) => {
    return list.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateProjectController::list
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
 */
list.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateProjectController::list
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
 */
list.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: list.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateProjectController::list
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
 */
    const listForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: list.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateProjectController::list
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
 */
        listForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: list.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateProjectController::list
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
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
const projects = {
    list: Object.assign(list, list),
}

export default projects