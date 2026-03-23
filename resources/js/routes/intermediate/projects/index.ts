import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateProjectController::list
 * @see app/Http/Controllers/IntermediateProjectController.php:23
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
 * @see app/Http/Controllers/IntermediateProjectController.php:23
 * @route '/intermediate/projects'
 */
list.url = (options?: RouteQueryOptions) => {
    return list.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateProjectController::list
 * @see app/Http/Controllers/IntermediateProjectController.php:23
 * @route '/intermediate/projects'
 */
list.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateProjectController::list
 * @see app/Http/Controllers/IntermediateProjectController.php:23
 * @route '/intermediate/projects'
 */
list.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: list.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateProjectController::list
 * @see app/Http/Controllers/IntermediateProjectController.php:23
 * @route '/intermediate/projects'
 */
    const listForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: list.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateProjectController::list
 * @see app/Http/Controllers/IntermediateProjectController.php:23
 * @route '/intermediate/projects'
 */
        listForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: list.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateProjectController::list
 * @see app/Http/Controllers/IntermediateProjectController.php:23
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
/**
* @see \App\Http\Controllers\IntermediateProjectController::register
 * @see app/Http/Controllers/IntermediateProjectController.php:42
 * @route '/intermediate/projects/register'
 */
export const register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

register.definition = {
    methods: ["get","head"],
    url: '/intermediate/projects/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateProjectController::register
 * @see app/Http/Controllers/IntermediateProjectController.php:42
 * @route '/intermediate/projects/register'
 */
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateProjectController::register
 * @see app/Http/Controllers/IntermediateProjectController.php:42
 * @route '/intermediate/projects/register'
 */
register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateProjectController::register
 * @see app/Http/Controllers/IntermediateProjectController.php:42
 * @route '/intermediate/projects/register'
 */
register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: register.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateProjectController::register
 * @see app/Http/Controllers/IntermediateProjectController.php:42
 * @route '/intermediate/projects/register'
 */
    const registerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: register.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateProjectController::register
 * @see app/Http/Controllers/IntermediateProjectController.php:42
 * @route '/intermediate/projects/register'
 */
        registerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: register.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateProjectController::register
 * @see app/Http/Controllers/IntermediateProjectController.php:42
 * @route '/intermediate/projects/register'
 */
        registerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: register.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    register.form = registerForm
/**
* @see \App\Http\Controllers\IntermediateProjectController::store
 * @see app/Http/Controllers/IntermediateProjectController.php:47
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
 * @see app/Http/Controllers/IntermediateProjectController.php:47
 * @route '/intermediate/projects'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateProjectController::store
 * @see app/Http/Controllers/IntermediateProjectController.php:47
 * @route '/intermediate/projects'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateProjectController::store
 * @see app/Http/Controllers/IntermediateProjectController.php:47
 * @route '/intermediate/projects'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateProjectController::store
 * @see app/Http/Controllers/IntermediateProjectController.php:47
 * @route '/intermediate/projects'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const projects = {
    list: Object.assign(list, list),
register: Object.assign(register, register),
store: Object.assign(store, store),
}

export default projects