import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::index
 * @see app/Http/Controllers/IntermediateRequisitionController.php:23
 * @see app/Http/Controllers/IntermediateRequisitionController.php:26
 * @route '/intermediate/resource-requisitions'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/intermediate/resource-requisitions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::index
 * @see app/Http/Controllers/IntermediateRequisitionController.php:23
 * @see app/Http/Controllers/IntermediateRequisitionController.php:26
 * @route '/intermediate/resource-requisitions'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::index
 * @see app/Http/Controllers/IntermediateRequisitionController.php:23
 * @see app/Http/Controllers/IntermediateRequisitionController.php:26
 * @route '/intermediate/resource-requisitions'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::index
 * @see app/Http/Controllers/IntermediateRequisitionController.php:23
 * @see app/Http/Controllers/IntermediateRequisitionController.php:26
 * @route '/intermediate/resource-requisitions'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::index
 * @see app/Http/Controllers/IntermediateRequisitionController.php:23
 * @see app/Http/Controllers/IntermediateRequisitionController.php:26
 * @route '/intermediate/resource-requisitions'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::index
 * @see app/Http/Controllers/IntermediateRequisitionController.php:23
 * @see app/Http/Controllers/IntermediateRequisitionController.php:26
 * @route '/intermediate/resource-requisitions'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::index
 * @see app/Http/Controllers/IntermediateRequisitionController.php:23
 * @see app/Http/Controllers/IntermediateRequisitionController.php:26
 * @route '/intermediate/resource-requisitions'
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
* @see \App\Http\Controllers\IntermediateRequisitionController::register
 * @see app/Http/Controllers/IntermediateRequisitionController.php:41
 * @see app/Http/Controllers/IntermediateRequisitionController.php:44
 * @route '/intermediate/resource-requisitions/register'
 */
export const register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

register.definition = {
    methods: ["get","head"],
    url: '/intermediate/resource-requisitions/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::register
 * @see app/Http/Controllers/IntermediateRequisitionController.php:41
 * @see app/Http/Controllers/IntermediateRequisitionController.php:44
 * @route '/intermediate/resource-requisitions/register'
 */
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::register
 * @see app/Http/Controllers/IntermediateRequisitionController.php:41
 * @see app/Http/Controllers/IntermediateRequisitionController.php:44
 * @route '/intermediate/resource-requisitions/register'
 */
register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::register
 * @see app/Http/Controllers/IntermediateRequisitionController.php:41
 * @see app/Http/Controllers/IntermediateRequisitionController.php:44
 * @route '/intermediate/resource-requisitions/register'
 */
register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: register.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::register
 * @see app/Http/Controllers/IntermediateRequisitionController.php:41
 * @see app/Http/Controllers/IntermediateRequisitionController.php:44
 * @route '/intermediate/resource-requisitions/register'
 */
    const registerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: register.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::register
 * @see app/Http/Controllers/IntermediateRequisitionController.php:41
 * @see app/Http/Controllers/IntermediateRequisitionController.php:44
 * @route '/intermediate/resource-requisitions/register'
 */
        registerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: register.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::register
 * @see app/Http/Controllers/IntermediateRequisitionController.php:41
 * @see app/Http/Controllers/IntermediateRequisitionController.php:44
 * @route '/intermediate/resource-requisitions/register'
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
* @see \App\Http\Controllers\IntermediateRequisitionController::store
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @see app/Http/Controllers/IntermediateRequisitionController.php:53
 * @route '/intermediate/resource-requisitions'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/intermediate/resource-requisitions',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::store
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @see app/Http/Controllers/IntermediateRequisitionController.php:53
 * @route '/intermediate/resource-requisitions'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::store
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @see app/Http/Controllers/IntermediateRequisitionController.php:53
 * @route '/intermediate/resource-requisitions'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::store
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @see app/Http/Controllers/IntermediateRequisitionController.php:53
 * @route '/intermediate/resource-requisitions'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::store
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @see app/Http/Controllers/IntermediateRequisitionController.php:53
 * @route '/intermediate/resource-requisitions'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::show
 * @see app/Http/Controllers/IntermediateRequisitionController.php:46
 * @see app/Http/Controllers/IntermediateRequisitionController.php:78
 * @route '/intermediate/resource-requisitions/{id}'
 */
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/intermediate/resource-requisitions/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::show
 * @see app/Http/Controllers/IntermediateRequisitionController.php:46
 * @see app/Http/Controllers/IntermediateRequisitionController.php:78
 * @route '/intermediate/resource-requisitions/{id}'
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
* @see \App\Http\Controllers\IntermediateRequisitionController::show
 * @see app/Http/Controllers/IntermediateRequisitionController.php:46
 * @see app/Http/Controllers/IntermediateRequisitionController.php:78
 * @route '/intermediate/resource-requisitions/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::show
 * @see app/Http/Controllers/IntermediateRequisitionController.php:46
 * @see app/Http/Controllers/IntermediateRequisitionController.php:78
 * @route '/intermediate/resource-requisitions/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::show
 * @see app/Http/Controllers/IntermediateRequisitionController.php:46
 * @see app/Http/Controllers/IntermediateRequisitionController.php:78
 * @route '/intermediate/resource-requisitions/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::show
 * @see app/Http/Controllers/IntermediateRequisitionController.php:46
 * @see app/Http/Controllers/IntermediateRequisitionController.php:78
 * @route '/intermediate/resource-requisitions/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::show
 * @see app/Http/Controllers/IntermediateRequisitionController.php:46
 * @see app/Http/Controllers/IntermediateRequisitionController.php:78
 * @route '/intermediate/resource-requisitions/{id}'
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
* @see \App\Http\Controllers\IntermediateRequisitionController::edit
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @route '/intermediate/resource-requisitions/{id}/edit'
 */
export const edit = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/intermediate/resource-requisitions/{id}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::edit
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @route '/intermediate/resource-requisitions/{id}/edit'
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
* @see \App\Http\Controllers\IntermediateRequisitionController::edit
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @route '/intermediate/resource-requisitions/{id}/edit'
 */
edit.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::edit
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @route '/intermediate/resource-requisitions/{id}/edit'
 */
edit.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::edit
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @route '/intermediate/resource-requisitions/{id}/edit'
 */
    const editForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::edit
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @route '/intermediate/resource-requisitions/{id}/edit'
 */
        editForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::edit
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @route '/intermediate/resource-requisitions/{id}/edit'
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
* @see \App\Http\Controllers\IntermediateRequisitionController::update
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @route '/intermediate/resource-requisitions/{id}/update'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/intermediate/resource-requisitions/{id}/update',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::update
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @route '/intermediate/resource-requisitions/{id}/update'
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
* @see \App\Http\Controllers\IntermediateRequisitionController::update
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @route '/intermediate/resource-requisitions/{id}/update'
 */
update.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::update
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @route '/intermediate/resource-requisitions/{id}/update'
 */
    const updateForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::update
 * @see app/Http/Controllers/IntermediateRequisitionController.php:0
 * @route '/intermediate/resource-requisitions/{id}/update'
 */
        updateForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, options),
            method: 'post',
        })
    
    update.form = updateForm
const requisitions = {
    index: Object.assign(index, index),
register: Object.assign(register, register),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
}

export default requisitions