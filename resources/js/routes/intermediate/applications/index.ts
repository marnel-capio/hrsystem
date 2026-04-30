import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateApplicationController::updatePaperScreening
 * @see app/Http/Controllers/IntermediateApplicationController.php:556
 * @route '/intermediate/applications/{id}/update-paper-screening'
 */
export const updatePaperScreening = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updatePaperScreening.url(args, options),
    method: 'post',
})

updatePaperScreening.definition = {
    methods: ["post"],
    url: '/intermediate/applications/{id}/update-paper-screening',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::updatePaperScreening
 * @see app/Http/Controllers/IntermediateApplicationController.php:556
 * @route '/intermediate/applications/{id}/update-paper-screening'
 */
updatePaperScreening.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return updatePaperScreening.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicationController::updatePaperScreening
 * @see app/Http/Controllers/IntermediateApplicationController.php:556
 * @route '/intermediate/applications/{id}/update-paper-screening'
 */
updatePaperScreening.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updatePaperScreening.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::updatePaperScreening
 * @see app/Http/Controllers/IntermediateApplicationController.php:556
 * @route '/intermediate/applications/{id}/update-paper-screening'
 */
    const updatePaperScreeningForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updatePaperScreening.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::updatePaperScreening
 * @see app/Http/Controllers/IntermediateApplicationController.php:556
 * @route '/intermediate/applications/{id}/update-paper-screening'
 */
        updatePaperScreeningForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updatePaperScreening.url(args, options),
            method: 'post',
        })
    
    updatePaperScreening.form = updatePaperScreeningForm
/**
* @see \App\Http\Controllers\IntermediateApplicationController::sendNotification
 * @see app/Http/Controllers/IntermediateApplicationController.php:1637
 * @route '/intermediate/applications/{applicationId}/send-notification'
 */
export const sendNotification = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendNotification.url(args, options),
    method: 'post',
})

sendNotification.definition = {
    methods: ["post"],
    url: '/intermediate/applications/{applicationId}/send-notification',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::sendNotification
 * @see app/Http/Controllers/IntermediateApplicationController.php:1637
 * @route '/intermediate/applications/{applicationId}/send-notification'
 */
sendNotification.url = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { applicationId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    applicationId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicationId: args.applicationId,
                }

    return sendNotification.definition.url
            .replace('{applicationId}', parsedArgs.applicationId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicationController::sendNotification
 * @see app/Http/Controllers/IntermediateApplicationController.php:1637
 * @route '/intermediate/applications/{applicationId}/send-notification'
 */
sendNotification.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendNotification.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::sendNotification
 * @see app/Http/Controllers/IntermediateApplicationController.php:1637
 * @route '/intermediate/applications/{applicationId}/send-notification'
 */
    const sendNotificationForm = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: sendNotification.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::sendNotification
 * @see app/Http/Controllers/IntermediateApplicationController.php:1637
 * @route '/intermediate/applications/{applicationId}/send-notification'
 */
        sendNotificationForm.post = (args: { applicationId: string | number } | [applicationId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: sendNotification.url(args, options),
            method: 'post',
        })
    
    sendNotification.form = sendNotificationForm
/**
* @see \App\Http\Controllers\IntermediateApplicationController::update
 * @see app/Http/Controllers/IntermediateApplicationController.php:834
 * @route '/intermediate/applications/{id}'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/intermediate/applications/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::update
 * @see app/Http/Controllers/IntermediateApplicationController.php:834
 * @route '/intermediate/applications/{id}'
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
* @see \App\Http\Controllers\IntermediateApplicationController::update
 * @see app/Http/Controllers/IntermediateApplicationController.php:834
 * @route '/intermediate/applications/{id}'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::update
 * @see app/Http/Controllers/IntermediateApplicationController.php:834
 * @route '/intermediate/applications/{id}'
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
* @see \App\Http\Controllers\IntermediateApplicationController::update
 * @see app/Http/Controllers/IntermediateApplicationController.php:834
 * @route '/intermediate/applications/{id}'
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
* @see \App\Http\Controllers\IntermediateApplicationController::print
 * @see app/Http/Controllers/IntermediateApplicationController.php:1523
 * @route '/intermediate/applications/{id}/print'
 */
export const print = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: print.url(args, options),
    method: 'get',
})

print.definition = {
    methods: ["get","head"],
    url: '/intermediate/applications/{id}/print',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::print
 * @see app/Http/Controllers/IntermediateApplicationController.php:1523
 * @route '/intermediate/applications/{id}/print'
 */
print.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return print.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicationController::print
 * @see app/Http/Controllers/IntermediateApplicationController.php:1523
 * @route '/intermediate/applications/{id}/print'
 */
print.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: print.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicationController::print
 * @see app/Http/Controllers/IntermediateApplicationController.php:1523
 * @route '/intermediate/applications/{id}/print'
 */
print.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: print.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::print
 * @see app/Http/Controllers/IntermediateApplicationController.php:1523
 * @route '/intermediate/applications/{id}/print'
 */
    const printForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: print.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::print
 * @see app/Http/Controllers/IntermediateApplicationController.php:1523
 * @route '/intermediate/applications/{id}/print'
 */
        printForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: print.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicationController::print
 * @see app/Http/Controllers/IntermediateApplicationController.php:1523
 * @route '/intermediate/applications/{id}/print'
 */
        printForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: print.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    print.form = printForm
/**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:28
 * @route '/intermediate/applications'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/intermediate/applications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:28
 * @route '/intermediate/applications'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:28
 * @route '/intermediate/applications'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:28
 * @route '/intermediate/applications'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:28
 * @route '/intermediate/applications'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:28
 * @route '/intermediate/applications'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicationController::index
 * @see app/Http/Controllers/IntermediateApplicationController.php:28
 * @route '/intermediate/applications'
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
 * @see app/Http/Controllers/ApplicationImportController.php:538
 * @route '/intermediate/applications/import'
 */
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/intermediate/applications/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:538
 * @route '/intermediate/applications/import'
 */
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:538
 * @route '/intermediate/applications/import'
 */
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:538
 * @route '/intermediate/applications/import'
 */
    const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: importMethod.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ApplicationImportController::importMethod
 * @see app/Http/Controllers/ApplicationImportController.php:538
 * @route '/intermediate/applications/import'
 */
        importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: importMethod.url(options),
            method: 'post',
        })
    
    importMethod.form = importMethodForm
/**
* @see \App\Http\Controllers\IntermediateApplicationController::register
 * @see app/Http/Controllers/IntermediateApplicationController.php:74
 * @route '/intermediate/applications/register'
 */
export const register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

register.definition = {
    methods: ["get","head"],
    url: '/intermediate/applications/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::register
 * @see app/Http/Controllers/IntermediateApplicationController.php:74
 * @route '/intermediate/applications/register'
 */
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicationController::register
 * @see app/Http/Controllers/IntermediateApplicationController.php:74
 * @route '/intermediate/applications/register'
 */
register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicationController::register
 * @see app/Http/Controllers/IntermediateApplicationController.php:74
 * @route '/intermediate/applications/register'
 */
register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: register.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::register
 * @see app/Http/Controllers/IntermediateApplicationController.php:74
 * @route '/intermediate/applications/register'
 */
    const registerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: register.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::register
 * @see app/Http/Controllers/IntermediateApplicationController.php:74
 * @route '/intermediate/applications/register'
 */
        registerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: register.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicationController::register
 * @see app/Http/Controllers/IntermediateApplicationController.php:74
 * @route '/intermediate/applications/register'
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
* @see \App\Http\Controllers\IntermediateApplicationController::store
 * @see app/Http/Controllers/IntermediateApplicationController.php:141
 * @route '/intermediate/applications'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/intermediate/applications',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::store
 * @see app/Http/Controllers/IntermediateApplicationController.php:141
 * @route '/intermediate/applications'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicationController::store
 * @see app/Http/Controllers/IntermediateApplicationController.php:141
 * @route '/intermediate/applications'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::store
 * @see app/Http/Controllers/IntermediateApplicationController.php:141
 * @route '/intermediate/applications'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::store
 * @see app/Http/Controllers/IntermediateApplicationController.php:141
 * @route '/intermediate/applications'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\IntermediateApplicationController::show
 * @see app/Http/Controllers/IntermediateApplicationController.php:346
 * @route '/intermediate/applications/{id}'
 */
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/intermediate/applications/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::show
 * @see app/Http/Controllers/IntermediateApplicationController.php:346
 * @route '/intermediate/applications/{id}'
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
* @see \App\Http\Controllers\IntermediateApplicationController::show
 * @see app/Http/Controllers/IntermediateApplicationController.php:346
 * @route '/intermediate/applications/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicationController::show
 * @see app/Http/Controllers/IntermediateApplicationController.php:346
 * @route '/intermediate/applications/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::show
 * @see app/Http/Controllers/IntermediateApplicationController.php:346
 * @route '/intermediate/applications/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::show
 * @see app/Http/Controllers/IntermediateApplicationController.php:346
 * @route '/intermediate/applications/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicationController::show
 * @see app/Http/Controllers/IntermediateApplicationController.php:346
 * @route '/intermediate/applications/{id}'
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
* @see \App\Http\Controllers\IntermediateApplicationController::edit
 * @see app/Http/Controllers/IntermediateApplicationController.php:632
 * @route '/intermediate/applications/{id}/edit'
 */
export const edit = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/intermediate/applications/{id}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::edit
 * @see app/Http/Controllers/IntermediateApplicationController.php:632
 * @route '/intermediate/applications/{id}/edit'
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
* @see \App\Http\Controllers\IntermediateApplicationController::edit
 * @see app/Http/Controllers/IntermediateApplicationController.php:632
 * @route '/intermediate/applications/{id}/edit'
 */
edit.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicationController::edit
 * @see app/Http/Controllers/IntermediateApplicationController.php:632
 * @route '/intermediate/applications/{id}/edit'
 */
edit.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::edit
 * @see app/Http/Controllers/IntermediateApplicationController.php:632
 * @route '/intermediate/applications/{id}/edit'
 */
    const editForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::edit
 * @see app/Http/Controllers/IntermediateApplicationController.php:632
 * @route '/intermediate/applications/{id}/edit'
 */
        editForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicationController::edit
 * @see app/Http/Controllers/IntermediateApplicationController.php:632
 * @route '/intermediate/applications/{id}/edit'
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
const applications = {
    updatePaperScreening: Object.assign(updatePaperScreening, updatePaperScreening),
sendNotification: Object.assign(sendNotification, sendNotification),
update: Object.assign(update, update),
print: Object.assign(print, print),
index: Object.assign(index, index),
import: Object.assign(importMethod, importMethod),
register: Object.assign(register, register),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
}

export default applications