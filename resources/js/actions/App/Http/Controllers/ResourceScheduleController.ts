import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ResourceScheduleController::index
 * @see app/Http/Controllers/ResourceScheduleController.php:18
 * @route '/action/schedules'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action/schedules',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ResourceScheduleController::index
 * @see app/Http/Controllers/ResourceScheduleController.php:18
 * @route '/action/schedules'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ResourceScheduleController::index
 * @see app/Http/Controllers/ResourceScheduleController.php:18
 * @route '/action/schedules'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ResourceScheduleController::index
 * @see app/Http/Controllers/ResourceScheduleController.php:18
 * @route '/action/schedules'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ResourceScheduleController::index
 * @see app/Http/Controllers/ResourceScheduleController.php:18
 * @route '/action/schedules'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ResourceScheduleController::index
 * @see app/Http/Controllers/ResourceScheduleController.php:18
 * @route '/action/schedules'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ResourceScheduleController::index
 * @see app/Http/Controllers/ResourceScheduleController.php:18
 * @route '/action/schedules'
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
* @see \App\Http\Controllers\ResourceScheduleController::create
 * @see app/Http/Controllers/ResourceScheduleController.php:31
 * @route '/action/schedules/register'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/action/schedules/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ResourceScheduleController::create
 * @see app/Http/Controllers/ResourceScheduleController.php:31
 * @route '/action/schedules/register'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ResourceScheduleController::create
 * @see app/Http/Controllers/ResourceScheduleController.php:31
 * @route '/action/schedules/register'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ResourceScheduleController::create
 * @see app/Http/Controllers/ResourceScheduleController.php:31
 * @route '/action/schedules/register'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ResourceScheduleController::create
 * @see app/Http/Controllers/ResourceScheduleController.php:31
 * @route '/action/schedules/register'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ResourceScheduleController::create
 * @see app/Http/Controllers/ResourceScheduleController.php:31
 * @route '/action/schedules/register'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ResourceScheduleController::create
 * @see app/Http/Controllers/ResourceScheduleController.php:31
 * @route '/action/schedules/register'
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
* @see \App\Http\Controllers\ResourceScheduleController::store
 * @see app/Http/Controllers/ResourceScheduleController.php:43
 * @route '/action/schedules'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/action/schedules',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ResourceScheduleController::store
 * @see app/Http/Controllers/ResourceScheduleController.php:43
 * @route '/action/schedules'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ResourceScheduleController::store
 * @see app/Http/Controllers/ResourceScheduleController.php:43
 * @route '/action/schedules'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ResourceScheduleController::store
 * @see app/Http/Controllers/ResourceScheduleController.php:43
 * @route '/action/schedules'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ResourceScheduleController::store
 * @see app/Http/Controllers/ResourceScheduleController.php:43
 * @route '/action/schedules'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\ResourceScheduleController::show
 * @see app/Http/Controllers/ResourceScheduleController.php:82
 * @route '/action/schedules/{id}'
 */
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/action/schedules/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ResourceScheduleController::show
 * @see app/Http/Controllers/ResourceScheduleController.php:82
 * @route '/action/schedules/{id}'
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
* @see \App\Http\Controllers\ResourceScheduleController::show
 * @see app/Http/Controllers/ResourceScheduleController.php:82
 * @route '/action/schedules/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ResourceScheduleController::show
 * @see app/Http/Controllers/ResourceScheduleController.php:82
 * @route '/action/schedules/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ResourceScheduleController::show
 * @see app/Http/Controllers/ResourceScheduleController.php:82
 * @route '/action/schedules/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ResourceScheduleController::show
 * @see app/Http/Controllers/ResourceScheduleController.php:82
 * @route '/action/schedules/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ResourceScheduleController::show
 * @see app/Http/Controllers/ResourceScheduleController.php:82
 * @route '/action/schedules/{id}'
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
* @see \App\Http\Controllers\ResourceScheduleController::edit
 * @see app/Http/Controllers/ResourceScheduleController.php:93
 * @route '/action/schedules/{id}/edit'
 */
export const edit = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/action/schedules/{id}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ResourceScheduleController::edit
 * @see app/Http/Controllers/ResourceScheduleController.php:93
 * @route '/action/schedules/{id}/edit'
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
* @see \App\Http\Controllers\ResourceScheduleController::edit
 * @see app/Http/Controllers/ResourceScheduleController.php:93
 * @route '/action/schedules/{id}/edit'
 */
edit.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ResourceScheduleController::edit
 * @see app/Http/Controllers/ResourceScheduleController.php:93
 * @route '/action/schedules/{id}/edit'
 */
edit.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ResourceScheduleController::edit
 * @see app/Http/Controllers/ResourceScheduleController.php:93
 * @route '/action/schedules/{id}/edit'
 */
    const editForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ResourceScheduleController::edit
 * @see app/Http/Controllers/ResourceScheduleController.php:93
 * @route '/action/schedules/{id}/edit'
 */
        editForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ResourceScheduleController::edit
 * @see app/Http/Controllers/ResourceScheduleController.php:93
 * @route '/action/schedules/{id}/edit'
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
* @see \App\Http\Controllers\ResourceScheduleController::update
 * @see app/Http/Controllers/ResourceScheduleController.php:106
 * @route '/action/schedules/{id}/update'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/action/schedules/{id}/update',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\ResourceScheduleController::update
 * @see app/Http/Controllers/ResourceScheduleController.php:106
 * @route '/action/schedules/{id}/update'
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
* @see \App\Http\Controllers\ResourceScheduleController::update
 * @see app/Http/Controllers/ResourceScheduleController.php:106
 * @route '/action/schedules/{id}/update'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\ResourceScheduleController::update
 * @see app/Http/Controllers/ResourceScheduleController.php:106
 * @route '/action/schedules/{id}/update'
 */
    const updateForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ResourceScheduleController::update
 * @see app/Http/Controllers/ResourceScheduleController.php:106
 * @route '/action/schedules/{id}/update'
 */
        updateForm.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\ResourceScheduleController::sendResourceScheduleNotification
 * @see app/Http/Controllers/ResourceScheduleController.php:149
 * @route '/action/schedules/{id}/send-notification'
 */
export const sendResourceScheduleNotification = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendResourceScheduleNotification.url(args, options),
    method: 'post',
})

sendResourceScheduleNotification.definition = {
    methods: ["post"],
    url: '/action/schedules/{id}/send-notification',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ResourceScheduleController::sendResourceScheduleNotification
 * @see app/Http/Controllers/ResourceScheduleController.php:149
 * @route '/action/schedules/{id}/send-notification'
 */
sendResourceScheduleNotification.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return sendResourceScheduleNotification.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ResourceScheduleController::sendResourceScheduleNotification
 * @see app/Http/Controllers/ResourceScheduleController.php:149
 * @route '/action/schedules/{id}/send-notification'
 */
sendResourceScheduleNotification.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendResourceScheduleNotification.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ResourceScheduleController::sendResourceScheduleNotification
 * @see app/Http/Controllers/ResourceScheduleController.php:149
 * @route '/action/schedules/{id}/send-notification'
 */
    const sendResourceScheduleNotificationForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: sendResourceScheduleNotification.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ResourceScheduleController::sendResourceScheduleNotification
 * @see app/Http/Controllers/ResourceScheduleController.php:149
 * @route '/action/schedules/{id}/send-notification'
 */
        sendResourceScheduleNotificationForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: sendResourceScheduleNotification.url(args, options),
            method: 'post',
        })
    
    sendResourceScheduleNotification.form = sendResourceScheduleNotificationForm
/**
* @see \App\Http\Controllers\ResourceScheduleController::destroy
 * @see app/Http/Controllers/ResourceScheduleController.php:182
 * @route '/action/schedules/{id}'
 */
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/action/schedules/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ResourceScheduleController::destroy
 * @see app/Http/Controllers/ResourceScheduleController.php:182
 * @route '/action/schedules/{id}'
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
* @see \App\Http\Controllers\ResourceScheduleController::destroy
 * @see app/Http/Controllers/ResourceScheduleController.php:182
 * @route '/action/schedules/{id}'
 */
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\ResourceScheduleController::destroy
 * @see app/Http/Controllers/ResourceScheduleController.php:182
 * @route '/action/schedules/{id}'
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
* @see \App\Http\Controllers\ResourceScheduleController::destroy
 * @see app/Http/Controllers/ResourceScheduleController.php:182
 * @route '/action/schedules/{id}'
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
const ResourceScheduleController = { index, create, store, show, edit, update, sendResourceScheduleNotification, destroy }

export default ResourceScheduleController