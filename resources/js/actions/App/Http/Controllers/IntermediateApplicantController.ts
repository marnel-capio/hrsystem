import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:14
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
 * @see app/Http/Controllers/IntermediateApplicantController.php:14
 * @route '/intermediate/applicants'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:14
 * @route '/intermediate/applicants'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:14
 * @route '/intermediate/applicants'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:14
 * @route '/intermediate/applicants'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:14
 * @route '/intermediate/applicants'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicantController::index
 * @see app/Http/Controllers/IntermediateApplicantController.php:14
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
* @see \App\Http\Controllers\IntermediateApplicantController::create
 * @see app/Http/Controllers/IntermediateApplicantController.php:24
 * @route '/intermediate/applicants/register'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/intermediate/applicants/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\IntermediateApplicantController::create
 * @see app/Http/Controllers/IntermediateApplicantController.php:24
 * @route '/intermediate/applicants/register'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicantController::create
 * @see app/Http/Controllers/IntermediateApplicantController.php:24
 * @route '/intermediate/applicants/register'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicantController::create
 * @see app/Http/Controllers/IntermediateApplicantController.php:24
 * @route '/intermediate/applicants/register'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantController::create
 * @see app/Http/Controllers/IntermediateApplicantController.php:24
 * @route '/intermediate/applicants/register'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantController::create
 * @see app/Http/Controllers/IntermediateApplicantController.php:24
 * @route '/intermediate/applicants/register'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicantController::create
 * @see app/Http/Controllers/IntermediateApplicantController.php:24
 * @route '/intermediate/applicants/register'
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
* @see \App\Http\Controllers\IntermediateApplicantController::store
 * @see app/Http/Controllers/IntermediateApplicantController.php:33
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
 * @see app/Http/Controllers/IntermediateApplicantController.php:33
 * @route '/intermediate/applicants'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicantController::store
 * @see app/Http/Controllers/IntermediateApplicantController.php:33
 * @route '/intermediate/applicants'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantController::store
 * @see app/Http/Controllers/IntermediateApplicantController.php:33
 * @route '/intermediate/applicants'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantController::store
 * @see app/Http/Controllers/IntermediateApplicantController.php:33
 * @route '/intermediate/applicants'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\IntermediateApplicantController::checkEmail
 * @see app/Http/Controllers/IntermediateApplicantController.php:66
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
 * @see app/Http/Controllers/IntermediateApplicantController.php:66
 * @route '/intermediate/applicants/check-email'
 */
checkEmail.url = (options?: RouteQueryOptions) => {
    return checkEmail.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicantController::checkEmail
 * @see app/Http/Controllers/IntermediateApplicantController.php:66
 * @route '/intermediate/applicants/check-email'
 */
checkEmail.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkEmail.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantController::checkEmail
 * @see app/Http/Controllers/IntermediateApplicantController.php:66
 * @route '/intermediate/applicants/check-email'
 */
    const checkEmailForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkEmail.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantController::checkEmail
 * @see app/Http/Controllers/IntermediateApplicantController.php:66
 * @route '/intermediate/applicants/check-email'
 */
        checkEmailForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkEmail.url(options),
            method: 'post',
        })
    
    checkEmail.form = checkEmailForm
/**
* @see \App\Http\Controllers\IntermediateApplicantController::show
 * @see app/Http/Controllers/IntermediateApplicantController.php:75
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
 * @see app/Http/Controllers/IntermediateApplicantController.php:75
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
 * @see app/Http/Controllers/IntermediateApplicantController.php:75
 * @route '/intermediate/applicants/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\IntermediateApplicantController::show
 * @see app/Http/Controllers/IntermediateApplicantController.php:75
 * @route '/intermediate/applicants/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicantController::show
 * @see app/Http/Controllers/IntermediateApplicantController.php:75
 * @route '/intermediate/applicants/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicantController::show
 * @see app/Http/Controllers/IntermediateApplicantController.php:75
 * @route '/intermediate/applicants/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\IntermediateApplicantController::show
 * @see app/Http/Controllers/IntermediateApplicantController.php:75
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
const IntermediateApplicantController = { index, create, store, checkEmail, show }

export default IntermediateApplicantController