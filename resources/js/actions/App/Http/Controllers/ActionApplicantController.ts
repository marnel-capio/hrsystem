import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:18
 * @route '/action/applicants'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action/applicants',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:18
 * @route '/action/applicants'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:18
 * @route '/action/applicants'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:18
 * @route '/action/applicants'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:18
 * @route '/action/applicants'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:18
 * @route '/action/applicants'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicantController::index
 * @see app/Http/Controllers/ActionApplicantController.php:18
 * @route '/action/applicants'
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
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:107
 * @route '/action/applicants/register'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/action/applicants/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:107
 * @route '/action/applicants/register'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:107
 * @route '/action/applicants/register'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:107
 * @route '/action/applicants/register'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:107
 * @route '/action/applicants/register'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:107
 * @route '/action/applicants/register'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicantController::create
 * @see app/Http/Controllers/ActionApplicantController.php:107
 * @route '/action/applicants/register'
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
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:116
 * @route '/action/applicants'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/action/applicants',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:116
 * @route '/action/applicants'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:116
 * @route '/action/applicants'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:116
 * @route '/action/applicants'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::store
 * @see app/Http/Controllers/ActionApplicantController.php:116
 * @route '/action/applicants'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\ActionApplicantController::checkEmail
 * @see app/Http/Controllers/ActionApplicantController.php:150
 * @route '/action/applicants/check-email'
 */
export const checkEmail = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkEmail.url(options),
    method: 'post',
})

checkEmail.definition = {
    methods: ["post"],
    url: '/action/applicants/check-email',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::checkEmail
 * @see app/Http/Controllers/ActionApplicantController.php:150
 * @route '/action/applicants/check-email'
 */
checkEmail.url = (options?: RouteQueryOptions) => {
    return checkEmail.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicantController::checkEmail
 * @see app/Http/Controllers/ActionApplicantController.php:150
 * @route '/action/applicants/check-email'
 */
checkEmail.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkEmail.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::checkEmail
 * @see app/Http/Controllers/ActionApplicantController.php:150
 * @route '/action/applicants/check-email'
 */
    const checkEmailForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkEmail.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::checkEmail
 * @see app/Http/Controllers/ActionApplicantController.php:150
 * @route '/action/applicants/check-email'
 */
        checkEmailForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkEmail.url(options),
            method: 'post',
        })
    
    checkEmail.form = checkEmailForm
/**
* @see \App\Http\Controllers\ActionApplicantController::show
 * @see app/Http/Controllers/ActionApplicantController.php:30
 * @route '/action/applicants/{id}'
 */
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/action/applicants/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::show
 * @see app/Http/Controllers/ActionApplicantController.php:30
 * @route '/action/applicants/{id}'
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
* @see \App\Http\Controllers\ActionApplicantController::show
 * @see app/Http/Controllers/ActionApplicantController.php:30
 * @route '/action/applicants/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicantController::show
 * @see app/Http/Controllers/ActionApplicantController.php:30
 * @route '/action/applicants/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::show
 * @see app/Http/Controllers/ActionApplicantController.php:30
 * @route '/action/applicants/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::show
 * @see app/Http/Controllers/ActionApplicantController.php:30
 * @route '/action/applicants/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicantController::show
 * @see app/Http/Controllers/ActionApplicantController.php:30
 * @route '/action/applicants/{id}'
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
* @see \App\Http\Controllers\ActionApplicantController::edit
 * @see app/Http/Controllers/ActionApplicantController.php:55
 * @route '/action/applicants/{id}/edit'
 */
export const edit = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/action/applicants/{id}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::edit
 * @see app/Http/Controllers/ActionApplicantController.php:55
 * @route '/action/applicants/{id}/edit'
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
* @see \App\Http\Controllers\ActionApplicantController::edit
 * @see app/Http/Controllers/ActionApplicantController.php:55
 * @route '/action/applicants/{id}/edit'
 */
edit.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicantController::edit
 * @see app/Http/Controllers/ActionApplicantController.php:55
 * @route '/action/applicants/{id}/edit'
 */
edit.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::edit
 * @see app/Http/Controllers/ActionApplicantController.php:55
 * @route '/action/applicants/{id}/edit'
 */
    const editForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicantController::edit
 * @see app/Http/Controllers/ActionApplicantController.php:55
 * @route '/action/applicants/{id}/edit'
 */
        editForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicantController::edit
 * @see app/Http/Controllers/ActionApplicantController.php:55
 * @route '/action/applicants/{id}/edit'
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
* @see \App\Http\Controllers\ActionApplicantController::update
 * @see app/Http/Controllers/ActionApplicantController.php:72
 * @route '/action/applicants/{id}/update'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/action/applicants/{id}/update',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\ActionApplicantController::update
 * @see app/Http/Controllers/ActionApplicantController.php:72
 * @route '/action/applicants/{id}/update'
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
* @see \App\Http\Controllers\ActionApplicantController::update
 * @see app/Http/Controllers/ActionApplicantController.php:72
 * @route '/action/applicants/{id}/update'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\ActionApplicantController::update
 * @see app/Http/Controllers/ActionApplicantController.php:72
 * @route '/action/applicants/{id}/update'
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
* @see \App\Http\Controllers\ActionApplicantController::update
 * @see app/Http/Controllers/ActionApplicantController.php:72
 * @route '/action/applicants/{id}/update'
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
const ActionApplicantController = { index, create, store, checkEmail, show, edit, update }

export default ActionApplicantController