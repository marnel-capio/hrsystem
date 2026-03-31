import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action/applicants',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:14
 * @route '/action/applicants'
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
const ActionApplicantController = { index }

export default ActionApplicantController