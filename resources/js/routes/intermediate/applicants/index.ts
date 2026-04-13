import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:10
 * @route '/intermediate/applicants'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/intermediate/applicants',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:10
 * @route '/intermediate/applicants'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:10
 * @route '/intermediate/applicants'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:10
 * @route '/intermediate/applicants'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:10
 * @route '/intermediate/applicants'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:10
 * @route '/intermediate/applicants'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:10
 * @route '/intermediate/applicants'
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
const applicants = {
    index: Object.assign(index, index),
}

export default applicants