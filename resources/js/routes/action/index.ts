import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import schedules from './schedules'
/**
 * @see routes/web.php:88
 * @route '/action'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web.php:88
 * @route '/action'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
 * @see routes/web.php:88
 * @route '/action'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
 * @see routes/web.php:88
 * @route '/action'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
 * @see routes/web.php:88
 * @route '/action'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
 * @see routes/web.php:88
 * @route '/action'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
 * @see routes/web.php:88
 * @route '/action'
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
 * @see routes/web.php:91
 * @route '/action/applications'
 */
export const applications = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: applications.url(options),
    method: 'get',
})

applications.definition = {
    methods: ["get","head"],
    url: '/action/applications',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web.php:91
 * @route '/action/applications'
 */
applications.url = (options?: RouteQueryOptions) => {
    return applications.definition.url + queryParams(options)
}

/**
 * @see routes/web.php:91
 * @route '/action/applications'
 */
applications.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: applications.url(options),
    method: 'get',
})
/**
 * @see routes/web.php:91
 * @route '/action/applications'
 */
applications.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: applications.url(options),
    method: 'head',
})

    /**
 * @see routes/web.php:91
 * @route '/action/applications'
 */
    const applicationsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: applications.url(options),
        method: 'get',
    })

            /**
 * @see routes/web.php:91
 * @route '/action/applications'
 */
        applicationsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: applications.url(options),
            method: 'get',
        })
            /**
 * @see routes/web.php:91
 * @route '/action/applications'
 */
        applicationsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: applications.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    applications.form = applicationsForm
/**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:12
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
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
list.url = (options?: RouteQueryOptions) => {
    return list.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
list.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
list.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: list.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
    const listForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: list.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:12
 * @route '/action/batches'
 */
        listForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: list.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::list
 * @see app/Http/Controllers/ActionBatchController.php:12
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
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/action/batches/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::create
 * @see app/Http/Controllers/ActionBatchController.php:37
 * @route '/action/batches/register'
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
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:89
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
 * @see app/Http/Controllers/ActionBatchController.php:89
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
 * @see app/Http/Controllers/ActionBatchController.php:89
 * @route '/action/batches/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:89
 * @route '/action/batches/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:89
 * @route '/action/batches/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:89
 * @route '/action/batches/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionBatchController::show
 * @see app/Http/Controllers/ActionBatchController.php:89
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
const action = {
    schedules: Object.assign(schedules, schedules),
index: Object.assign(index, index),
applications: Object.assign(applications, applications),
list: Object.assign(list, list),
create: Object.assign(create, create),
show: Object.assign(show, show),
}

export default action