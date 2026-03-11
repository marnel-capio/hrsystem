import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateProjectController::index
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/intermediate/projects',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateProjectController::index
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateProjectController::index
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateProjectController::index
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateProjectController::index
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateProjectController::index
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateProjectController::index
 * @see app/Http/Controllers/IntermediateProjectController.php:20
 * @route '/intermediate/projects'
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
const IntermediateProjectController = { index }

export default IntermediateProjectController