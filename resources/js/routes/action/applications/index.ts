import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ApplicationImportController::index
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action/applications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ApplicationImportController::index
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ApplicationImportController::index
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ApplicationImportController::index
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ApplicationImportController::index
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ApplicationImportController::index
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ApplicationImportController::index
 * @see app/Http/Controllers/ApplicationImportController.php:16
 * @route '/action/applications'
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
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:45
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
 * @see app/Http/Controllers/ApplicationImportController.php:45
 * @route '/applications/import'
 */
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:45
 * @route '/applications/import'
 */
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:45
 * @route '/applications/import'
 */
    const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: importMethod.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:45
 * @route '/applications/import'
 */
        importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: importMethod.url(options),
            method: 'post',
        })
    
    importMethod.form = importMethodForm
const applications = {
    index: Object.assign(index, index),
import: Object.assign(importMethod, importMethod),
}

export default applications