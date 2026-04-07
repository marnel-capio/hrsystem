import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
 */
export const list = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})

list.definition = {
    methods: ["get","head"],
    url: '/action/batches',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
 */
list.url = (options?: RouteQueryOptions) => {
    return list.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
 */
list.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
 */
list.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: list.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
 */
    const listForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: list.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
 */
        listForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: list.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:22
 * @route '/action/batches'
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
* @see \App\Http\Controllers\ActionBatchController::register
 * @see app/Http/Controllers/ActionBatchController.php:41
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
 * @see app/Http/Controllers/ActionBatchController.php:41
 * @route '/action/batches/register'
 */
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::register
 * @see app/Http/Controllers/ActionBatchController.php:41
 * @route '/action/batches/register'
 */
register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::register
 * @see app/Http/Controllers/ActionBatchController.php:41
 * @route '/action/batches/register'
 */
register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: register.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::register
 * @see app/Http/Controllers/ActionBatchController.php:41
 * @route '/action/batches/register'
 */
    const registerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: register.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::register
 * @see app/Http/Controllers/ActionBatchController.php:41
 * @route '/action/batches/register'
 */
        registerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: register.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::register
 * @see app/Http/Controllers/ActionBatchController.php:41
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
 * @see app/Http/Controllers/ActionBatchController.php:50
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
 * @see app/Http/Controllers/ActionBatchController.php:50
 * @route '/action/batches'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:50
 * @route '/action/batches'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:50
 * @route '/action/batches'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::store
 * @see app/Http/Controllers/ActionBatchController.php:50
 * @route '/action/batches'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:79
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
 * @see app/Http/Controllers/ActionBatchController.php:79
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
 * @see app/Http/Controllers/ActionBatchController.php:79
 * @route '/action/batches/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:79
 * @route '/action/batches/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:79
 * @route '/action/batches/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:79
 * @route '/action/batches/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:79
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
/**
* @see \App\Http\Controllers\ActionBatchController::edit
 * @see app/Http/Controllers/ActionBatchController.php:97
 * @route '/action/batches/{id}/edit'
 */
export const edit = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/action/batches/{id}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::edit
 * @see app/Http/Controllers/ActionBatchController.php:97
 * @route '/action/batches/{id}/edit'
 */
edit.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::edit
 * @see app/Http/Controllers/ActionBatchController.php:97
 * @route '/action/batches/{id}/edit'
 */
edit.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::edit
 * @see app/Http/Controllers/ActionBatchController.php:97
 * @route '/action/batches/{id}/edit'
 */
edit.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::edit
 * @see app/Http/Controllers/ActionBatchController.php:97
 * @route '/action/batches/{id}/edit'
 */
    const editForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::edit
 * @see app/Http/Controllers/ActionBatchController.php:97
 * @route '/action/batches/{id}/edit'
 */
        editForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::edit
 * @see app/Http/Controllers/ActionBatchController.php:97
 * @route '/action/batches/{id}/edit'
 */
        editForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\ActionBatchController::update
 * @see app/Http/Controllers/ActionBatchController.php:107
 * @route '/action/batches/{id}/update'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/action/batches/{id}/update',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionBatchController::update
 * @see app/Http/Controllers/ActionBatchController.php:107
 * @route '/action/batches/{id}/update'
 */
update.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::update
 * @see app/Http/Controllers/ActionBatchController.php:107
 * @route '/action/batches/{id}/update'
 */
update.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::update
 * @see app/Http/Controllers/ActionBatchController.php:107
 * @route '/action/batches/{id}/update'
 */
    const updateForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::update
 * @see app/Http/Controllers/ActionBatchController.php:107
 * @route '/action/batches/{id}/update'
 */
        updateForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, options),
            method: 'post',
        })
    
    update.form = updateForm
const batches = {
    list: Object.assign(list, list),
register: Object.assign(register, register),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
}

export default batches