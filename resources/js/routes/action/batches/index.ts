import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action/batches',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::index
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
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
* @see \App\Http\Controllers\ActionBatchController::register
 * @see app/Http/Controllers/ActionBatchController.php:40
 * @route '/action/batches/register'
 */
export const register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

register.definition = {
    methods: ["get","head"],
    url: '/action/batches/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::register
 * @see app/Http/Controllers/ActionBatchController.php:40
 * @route '/action/batches/register'
 */
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::register
 * @see app/Http/Controllers/ActionBatchController.php:40
 * @route '/action/batches/register'
 */
register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::register
 * @see app/Http/Controllers/ActionBatchController.php:40
 * @route '/action/batches/register'
 */
register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: register.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::register
 * @see app/Http/Controllers/ActionBatchController.php:40
 * @route '/action/batches/register'
 */
    const registerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: register.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::register
 * @see app/Http/Controllers/ActionBatchController.php:40
 * @route '/action/batches/register'
 */
        registerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: register.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::register
 * @see app/Http/Controllers/ActionBatchController.php:40
 * @route '/action/batches/register'
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
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:45
 * @route '/action/batches'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/action/batches',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:45
 * @route '/action/batches'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:45
 * @route '/action/batches'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:45
 * @route '/action/batches'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:45
 * @route '/action/batches'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:74
 * @route '/action/batches/{id}'
 */
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/action/batches/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:74
 * @route '/action/batches/{id}'
 */
show.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                }

    return show.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:74
 * @route '/action/batches/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:74
 * @route '/action/batches/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:74
 * @route '/action/batches/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:74
 * @route '/action/batches/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:74
 * @route '/action/batches/{id}'
 */
        showForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
const batches = {
    index: Object.assign(index, index),
register: Object.assign(register, register),
store: Object.assign(store, store),
show: Object.assign(show, show),
}

export default batches