import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateProjectController::store
 * @see app/Http/Controllers/IntermediateProjectController.php:23
 * @route '/intermediate/projects'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/intermediate/projects',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateProjectController::store
 * @see app/Http/Controllers/IntermediateProjectController.php:23
 * @route '/intermediate/projects'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateProjectController::store
 * @see app/Http/Controllers/IntermediateProjectController.php:23
 * @route '/intermediate/projects'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateProjectController::store
 * @see app/Http/Controllers/IntermediateProjectController.php:23
 * @route '/intermediate/projects'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateProjectController::store
 * @see app/Http/Controllers/IntermediateProjectController.php:23
 * @route '/intermediate/projects'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\IntermediateProjectController::index
 * @see app/Http/Controllers/IntermediateProjectController.php:0
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
 * @see app/Http/Controllers/IntermediateProjectController.php:0
 * @route '/intermediate/projects'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateProjectController::index
 * @see app/Http/Controllers/IntermediateProjectController.php:0
 * @route '/intermediate/projects'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateProjectController::index
 * @see app/Http/Controllers/IntermediateProjectController.php:0
 * @route '/intermediate/projects'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateProjectController::index
 * @see app/Http/Controllers/IntermediateProjectController.php:0
 * @route '/intermediate/projects'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateProjectController::index
 * @see app/Http/Controllers/IntermediateProjectController.php:0
 * @route '/intermediate/projects'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateProjectController::index
 * @see app/Http/Controllers/IntermediateProjectController.php:0
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
const IntermediateProjectController = { store, index }

export default IntermediateProjectController