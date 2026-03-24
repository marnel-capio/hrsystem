import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ApplicationImportController::create
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/action/applications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ApplicationImportController::create
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ApplicationImportController::create
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ApplicationImportController::create
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ApplicationImportController::create
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ApplicationImportController::create
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ApplicationImportController::create
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
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
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:40
 * @route '/applications/import'
 */
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/applications/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:40
 * @route '/applications/import'
 */
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:40
 * @route '/applications/import'
 */
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:40
 * @route '/applications/import'
 */
    const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: importMethod.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:40
 * @route '/applications/import'
 */
        importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: importMethod.url(options),
            method: 'post',
        })
    
    importMethod.form = importMethodForm
const ApplicationImportController = { create, importMethod, import: importMethod }

export default ApplicationImportController