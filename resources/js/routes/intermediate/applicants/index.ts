import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:17
 * @route '/intermediate/applicants'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/intermediate/applicants',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:17
 * @route '/intermediate/applicants'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:17
 * @route '/intermediate/applicants'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:17
 * @route '/intermediate/applicants'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:17
 * @route '/intermediate/applicants'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:17
 * @route '/intermediate/applicants'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:17
 * @route '/intermediate/applicants'
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
* @see \App\Http\Controllers\IntermediateApplicantController::register
 * @see app/Http/Controllers/IntermediateApplicantController.php:27
 * @route '/intermediate/applicants/register'
 */
export const register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

register.definition = {
    methods: ["get","head"],
    url: '/intermediate/applicants/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantController::register
 * @see app/Http/Controllers/IntermediateApplicantController.php:27
 * @route '/intermediate/applicants/register'
 */
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicantController::register
 * @see app/Http/Controllers/IntermediateApplicantController.php:27
 * @route '/intermediate/applicants/register'
 */
register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicantController::register
 * @see app/Http/Controllers/IntermediateApplicantController.php:27
 * @route '/intermediate/applicants/register'
 */
register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: register.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantController::register
 * @see app/Http/Controllers/IntermediateApplicantController.php:27
 * @route '/intermediate/applicants/register'
 */
    const registerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: register.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantController::register
 * @see app/Http/Controllers/IntermediateApplicantController.php:27
 * @route '/intermediate/applicants/register'
 */
        registerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: register.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicantController::register
 * @see app/Http/Controllers/IntermediateApplicantController.php:27
 * @route '/intermediate/applicants/register'
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
* @see \App\Http\Controllers\IntermediateApplicantController::store
 * @see app/Http/Controllers/IntermediateApplicantController.php:38
 * @route '/intermediate/applicants'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/intermediate/applicants',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantController::store
 * @see app/Http/Controllers/IntermediateApplicantController.php:38
 * @route '/intermediate/applicants'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicantController::store
 * @see app/Http/Controllers/IntermediateApplicantController.php:38
 * @route '/intermediate/applicants'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantController::store
 * @see app/Http/Controllers/IntermediateApplicantController.php:38
 * @route '/intermediate/applicants'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantController::store
 * @see app/Http/Controllers/IntermediateApplicantController.php:38
 * @route '/intermediate/applicants'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\IntermediateApplicantController::checkEmail
 * @see app/Http/Controllers/IntermediateApplicantController.php:73
 * @route '/intermediate/applicants/check-email'
 */
export const checkEmail = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkEmail.url(options),
    method: 'post',
})

checkEmail.definition = {
    methods: ["post"],
    url: '/intermediate/applicants/check-email',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantController::checkEmail
 * @see app/Http/Controllers/IntermediateApplicantController.php:73
 * @route '/intermediate/applicants/check-email'
 */
checkEmail.url = (options?: RouteQueryOptions) => {
    return checkEmail.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicantController::checkEmail
 * @see app/Http/Controllers/IntermediateApplicantController.php:73
 * @route '/intermediate/applicants/check-email'
 */
checkEmail.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkEmail.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantController::checkEmail
 * @see app/Http/Controllers/IntermediateApplicantController.php:73
 * @route '/intermediate/applicants/check-email'
 */
    const checkEmailForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkEmail.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantController::checkEmail
 * @see app/Http/Controllers/IntermediateApplicantController.php:73
 * @route '/intermediate/applicants/check-email'
 */
        checkEmailForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkEmail.url(options),
            method: 'post',
        })
    
    checkEmail.form = checkEmailForm
/**
* @see \App\Http\Controllers\IntermediateApplicantController::show
 * @see app/Http/Controllers/IntermediateApplicantController.php:82
 * @route '/intermediate/applicants/{id}'
 */
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/intermediate/applicants/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantController::show
 * @see app/Http/Controllers/IntermediateApplicantController.php:82
 * @route '/intermediate/applicants/{id}'
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
* @see \App\Http\Controllers\IntermediateApplicantController::show
 * @see app/Http/Controllers/IntermediateApplicantController.php:82
 * @route '/intermediate/applicants/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicantController::show
 * @see app/Http/Controllers/IntermediateApplicantController.php:82
 * @route '/intermediate/applicants/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantController::show
 * @see app/Http/Controllers/IntermediateApplicantController.php:82
 * @route '/intermediate/applicants/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantController::show
 * @see app/Http/Controllers/IntermediateApplicantController.php:82
 * @route '/intermediate/applicants/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicantController::show
 * @see app/Http/Controllers/IntermediateApplicantController.php:82
 * @route '/intermediate/applicants/{id}'
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
* @see \App\Http\Controllers\IntermediateApplicantController::edit
 * @see app/Http/Controllers/IntermediateApplicantController.php:94
 * @route '/intermediate/applicants/{id}/edit'
 */
export const edit = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/intermediate/applicants/{id}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantController::edit
 * @see app/Http/Controllers/IntermediateApplicantController.php:94
 * @route '/intermediate/applicants/{id}/edit'
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
* @see \App\Http\Controllers\IntermediateApplicantController::edit
 * @see app/Http/Controllers/IntermediateApplicantController.php:94
 * @route '/intermediate/applicants/{id}/edit'
 */
edit.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicantController::edit
 * @see app/Http/Controllers/IntermediateApplicantController.php:94
 * @route '/intermediate/applicants/{id}/edit'
 */
edit.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantController::edit
 * @see app/Http/Controllers/IntermediateApplicantController.php:94
 * @route '/intermediate/applicants/{id}/edit'
 */
    const editForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantController::edit
 * @see app/Http/Controllers/IntermediateApplicantController.php:94
 * @route '/intermediate/applicants/{id}/edit'
 */
        editForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicantController::edit
 * @see app/Http/Controllers/IntermediateApplicantController.php:94
 * @route '/intermediate/applicants/{id}/edit'
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
* @see \App\Http\Controllers\IntermediateApplicantController::update
 * @see app/Http/Controllers/IntermediateApplicantController.php:108
 * @route '/intermediate/applicants/{id}/update'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/intermediate/applicants/{id}/update',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantController::update
 * @see app/Http/Controllers/IntermediateApplicantController.php:108
 * @route '/intermediate/applicants/{id}/update'
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
* @see \App\Http\Controllers\IntermediateApplicantController::update
 * @see app/Http/Controllers/IntermediateApplicantController.php:108
 * @route '/intermediate/applicants/{id}/update'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantController::update
 * @see app/Http/Controllers/IntermediateApplicantController.php:108
 * @route '/intermediate/applicants/{id}/update'
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
* @see \App\Http\Controllers\IntermediateApplicantController::update
 * @see app/Http/Controllers/IntermediateApplicantController.php:108
 * @route '/intermediate/applicants/{id}/update'
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
const applicants = {
    index: Object.assign(index, index),
register: Object.assign(register, register),
store: Object.assign(store, store),
checkEmail: Object.assign(checkEmail, checkEmail),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
}

export default applicants