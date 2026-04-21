import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/intermediate/applications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:0
 * @route '/intermediate/applications'
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
const IntermediateApplicationController = { index }

export default IntermediateApplicationController