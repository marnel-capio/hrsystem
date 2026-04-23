import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::index
 * @see app/Http/Controllers/IntermediateRequisitionController.php:31
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
 * @see app/Http/Controllers/IntermediateRequisitionController.php:31
 * @route '/intermediate/resource-requisitions'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::index
 * @see app/Http/Controllers/IntermediateRequisitionController.php:31
 * @route '/intermediate/resource-requisitions'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::index
 * @see app/Http/Controllers/IntermediateRequisitionController.php:31
 * @route '/intermediate/resource-requisitions'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::index
 * @see app/Http/Controllers/IntermediateRequisitionController.php:31
 * @route '/intermediate/resource-requisitions'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::index
 * @see app/Http/Controllers/IntermediateRequisitionController.php:31
 * @route '/intermediate/resource-requisitions'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::index
 * @see app/Http/Controllers/IntermediateRequisitionController.php:31
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
* @see \App\Http\Controllers\IntermediateRequisitionController::create
 * @see app/Http/Controllers/IntermediateRequisitionController.php:49
 * @route '/intermediate/resource-requisitions/register'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/intermediate/resource-requisitions/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::create
 * @see app/Http/Controllers/IntermediateRequisitionController.php:49
 * @route '/intermediate/resource-requisitions/register'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::create
 * @see app/Http/Controllers/IntermediateRequisitionController.php:49
 * @route '/intermediate/resource-requisitions/register'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::create
 * @see app/Http/Controllers/IntermediateRequisitionController.php:49
 * @route '/intermediate/resource-requisitions/register'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::create
 * @see app/Http/Controllers/IntermediateRequisitionController.php:49
 * @route '/intermediate/resource-requisitions/register'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::create
 * @see app/Http/Controllers/IntermediateRequisitionController.php:49
 * @route '/intermediate/resource-requisitions/register'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::create
 * @see app/Http/Controllers/IntermediateRequisitionController.php:49
 * @route '/intermediate/resource-requisitions/register'
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
* @see \App\Http\Controllers\IntermediateRequisitionController::store
 * @see app/Http/Controllers/IntermediateRequisitionController.php:58
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
 * @see app/Http/Controllers/IntermediateRequisitionController.php:58
 * @route '/intermediate/resource-requisitions'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::store
 * @see app/Http/Controllers/IntermediateRequisitionController.php:58
 * @route '/intermediate/resource-requisitions'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::store
 * @see app/Http/Controllers/IntermediateRequisitionController.php:58
 * @route '/intermediate/resource-requisitions'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::store
 * @see app/Http/Controllers/IntermediateRequisitionController.php:58
 * @route '/intermediate/resource-requisitions'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::show
 * @see app/Http/Controllers/IntermediateRequisitionController.php:85
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
 * @see app/Http/Controllers/IntermediateRequisitionController.php:85
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
 * @see app/Http/Controllers/IntermediateRequisitionController.php:85
 * @see app/Http/Controllers/IntermediateRequisitionController.php:78
 * @route '/intermediate/resource-requisitions/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::show
 * @see app/Http/Controllers/IntermediateRequisitionController.php:85
 * @see app/Http/Controllers/IntermediateRequisitionController.php:78
 * @route '/intermediate/resource-requisitions/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::show
 * @see app/Http/Controllers/IntermediateRequisitionController.php:85
 * @see app/Http/Controllers/IntermediateRequisitionController.php:78
 * @route '/intermediate/resource-requisitions/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::show
 * @see app/Http/Controllers/IntermediateRequisitionController.php:85
 * @see app/Http/Controllers/IntermediateRequisitionController.php:78
 * @route '/intermediate/resource-requisitions/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::show
 * @see app/Http/Controllers/IntermediateRequisitionController.php:85
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
 * @see app/Http/Controllers/IntermediateRequisitionController.php:216
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
 * @see app/Http/Controllers/IntermediateRequisitionController.php:216
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
 * @see app/Http/Controllers/IntermediateRequisitionController.php:216
 * @route '/intermediate/resource-requisitions/{id}/edit'
 */
edit.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::edit
 * @see app/Http/Controllers/IntermediateRequisitionController.php:216
 * @route '/intermediate/resource-requisitions/{id}/edit'
 */
edit.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::edit
 * @see app/Http/Controllers/IntermediateRequisitionController.php:216
 * @route '/intermediate/resource-requisitions/{id}/edit'
 */
    const editForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::edit
 * @see app/Http/Controllers/IntermediateRequisitionController.php:216
 * @route '/intermediate/resource-requisitions/{id}/edit'
 */
        editForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::edit
 * @see app/Http/Controllers/IntermediateRequisitionController.php:216
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
 * @see app/Http/Controllers/IntermediateRequisitionController.php:227
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
 * @see app/Http/Controllers/IntermediateRequisitionController.php:227
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
 * @see app/Http/Controllers/IntermediateRequisitionController.php:227
 * @route '/intermediate/resource-requisitions/{id}/update'
 */
update.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::update
 * @see app/Http/Controllers/IntermediateRequisitionController.php:227
 * @route '/intermediate/resource-requisitions/{id}/update'
 */
    const updateForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::update
 * @see app/Http/Controllers/IntermediateRequisitionController.php:227
 * @route '/intermediate/resource-requisitions/{id}/update'
 */
        updateForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, options),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::sendResourceRequisitionNotification
 * @see app/Http/Controllers/IntermediateRequisitionController.php:98
 * @route '/intermediate/resource-requisitions/{id}/send-notification'
 */
export const sendResourceRequisitionNotification = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendResourceRequisitionNotification.url(args, options),
    method: 'post',
})

sendResourceRequisitionNotification.definition = {
    methods: ["post"],
    url: '/intermediate/resource-requisitions/{id}/send-notification',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::sendResourceRequisitionNotification
 * @see app/Http/Controllers/IntermediateRequisitionController.php:98
 * @route '/intermediate/resource-requisitions/{id}/send-notification'
 */
sendResourceRequisitionNotification.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return sendResourceRequisitionNotification.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::sendResourceRequisitionNotification
 * @see app/Http/Controllers/IntermediateRequisitionController.php:98
 * @route '/intermediate/resource-requisitions/{id}/send-notification'
 */
sendResourceRequisitionNotification.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendResourceRequisitionNotification.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::sendResourceRequisitionNotification
 * @see app/Http/Controllers/IntermediateRequisitionController.php:98
 * @route '/intermediate/resource-requisitions/{id}/send-notification'
 */
    const sendResourceRequisitionNotificationForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: sendResourceRequisitionNotification.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::sendResourceRequisitionNotification
 * @see app/Http/Controllers/IntermediateRequisitionController.php:98
 * @route '/intermediate/resource-requisitions/{id}/send-notification'
 */
        sendResourceRequisitionNotificationForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: sendResourceRequisitionNotification.url(args, options),
            method: 'post',
        })
    
    sendResourceRequisitionNotification.form = sendResourceRequisitionNotificationForm
/**
* @see \App\Http\Controllers\IntermediateRequisitionController::destroy
 * @see app/Http/Controllers/IntermediateRequisitionController.php:134
 * @route '/intermediate/resource-requisitions/{id}'
 */
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/intermediate/resource-requisitions/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::destroy
 * @see app/Http/Controllers/IntermediateRequisitionController.php:134
 * @route '/intermediate/resource-requisitions/{id}'
 */
destroy.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateRequisitionController::destroy
 * @see app/Http/Controllers/IntermediateRequisitionController.php:134
 * @route '/intermediate/resource-requisitions/{id}'
 */
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\IntermediateRequisitionController::destroy
 * @see app/Http/Controllers/IntermediateRequisitionController.php:134
 * @route '/intermediate/resource-requisitions/{id}'
 */
    const destroyForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateRequisitionController::destroy
 * @see app/Http/Controllers/IntermediateRequisitionController.php:134
 * @route '/intermediate/resource-requisitions/{id}'
 */
        destroyForm.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const IntermediateRequisitionController = { index, create, store, show, edit, update, sendResourceRequisitionNotification, destroy }

export default IntermediateRequisitionController