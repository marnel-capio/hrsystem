import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:97
 * @route '/action/applications/import'
 */
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/action/applications/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:97
 * @route '/action/applications/import'
 */
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:97
 * @route '/action/applications/import'
 */
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:97
 * @route '/action/applications/import'
 */
    const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: importMethod.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:97
 * @route '/action/applications/import'
 */
        importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: importMethod.url(options),
            method: 'post',
        })
    
    importMethod.form = importMethodForm
/**
* @see \App\Http\Controllers\ApplicationImportController::importIntermediateApplicants
 * @see app/Http/Controllers/ApplicationImportController.php:516
 * @route '/intermediate/applications/import'
 */
export const importIntermediateApplicants = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importIntermediateApplicants.url(options),
    method: 'post',
})

importIntermediateApplicants.definition = {
    methods: ["post"],
    url: '/intermediate/applications/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ApplicationImportController::importIntermediateApplicants
 * @see app/Http/Controllers/ApplicationImportController.php:516
 * @route '/intermediate/applications/import'
 */
importIntermediateApplicants.url = (options?: RouteQueryOptions) => {
    return importIntermediateApplicants.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ApplicationImportController::importIntermediateApplicants
 * @see app/Http/Controllers/ApplicationImportController.php:516
 * @route '/intermediate/applications/import'
 */
importIntermediateApplicants.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importIntermediateApplicants.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ApplicationImportController::importIntermediateApplicants
 * @see app/Http/Controllers/ApplicationImportController.php:516
 * @route '/intermediate/applications/import'
 */
    const importIntermediateApplicantsForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: importIntermediateApplicants.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ApplicationImportController::importIntermediateApplicants
 * @see app/Http/Controllers/ApplicationImportController.php:516
 * @route '/intermediate/applications/import'
 */
        importIntermediateApplicantsForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: importIntermediateApplicants.url(options),
            method: 'post',
        })
    
    importIntermediateApplicants.form = importIntermediateApplicantsForm
const ApplicationImportController = { importMethod, importIntermediateApplicants, import: importMethod }

export default ApplicationImportController