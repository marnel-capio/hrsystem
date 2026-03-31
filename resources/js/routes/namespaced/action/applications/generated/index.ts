import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\ActionApplicationController::method0xDyEK08Y4TXtmK0
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
export const method0xDyEK08Y4TXtmK0 = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: method0xDyEK08Y4TXtmK0.url(args, options),
    method: 'get',
})

method0xDyEK08Y4TXtmK0.definition = {
    methods: ["get","head"],
    url: '/action/applications/{id}/emails',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::method0xDyEK08Y4TXtmK0
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
method0xDyEK08Y4TXtmK0.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return method0xDyEK08Y4TXtmK0.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::method0xDyEK08Y4TXtmK0
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
method0xDyEK08Y4TXtmK0.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: method0xDyEK08Y4TXtmK0.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::method0xDyEK08Y4TXtmK0
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
method0xDyEK08Y4TXtmK0.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: method0xDyEK08Y4TXtmK0.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::method0xDyEK08Y4TXtmK0
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
    const method0xDyEK08Y4TXtmK0Form = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: method0xDyEK08Y4TXtmK0.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::method0xDyEK08Y4TXtmK0
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
        method0xDyEK08Y4TXtmK0Form.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: method0xDyEK08Y4TXtmK0.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::method0xDyEK08Y4TXtmK0
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/emails'
 */
        method0xDyEK08Y4TXtmK0Form.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: method0xDyEK08Y4TXtmK0.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    method0xDyEK08Y4TXtmK0.form = method0xDyEK08Y4TXtmK0Form
/**
* @see \App\Http\Controllers\ActionApplicationController::rUHQm3gNskUeCDoX
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/notify'
 */
export const rUHQm3gNskUeCDoX = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: rUHQm3gNskUeCDoX.url(args, options),
    method: 'post',
})

rUHQm3gNskUeCDoX.definition = {
    methods: ["post"],
    url: '/action/applications/{id}/notify',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::rUHQm3gNskUeCDoX
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/notify'
 */
rUHQm3gNskUeCDoX.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return rUHQm3gNskUeCDoX.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::rUHQm3gNskUeCDoX
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/notify'
 */
rUHQm3gNskUeCDoX.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: rUHQm3gNskUeCDoX.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::rUHQm3gNskUeCDoX
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/notify'
 */
    const rUHQm3gNskUeCDoXForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: rUHQm3gNskUeCDoX.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::rUHQm3gNskUeCDoX
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/notify'
 */
        rUHQm3gNskUeCDoXForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: rUHQm3gNskUeCDoX.url(args, options),
            method: 'post',
        })
    
    rUHQm3gNskUeCDoX.form = rUHQm3gNskUeCDoXForm
/**
* @see \App\Http\Controllers\ActionApplicationController::method5KzWu5VzxL1uf7D1
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
export const method5KzWu5VzxL1uf7D1 = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: method5KzWu5VzxL1uf7D1.url(args, options),
    method: 'get',
})

method5KzWu5VzxL1uf7D1.definition = {
    methods: ["get","head"],
    url: '/action/applications/{id}/download',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::method5KzWu5VzxL1uf7D1
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
method5KzWu5VzxL1uf7D1.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return method5KzWu5VzxL1uf7D1.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::method5KzWu5VzxL1uf7D1
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
method5KzWu5VzxL1uf7D1.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: method5KzWu5VzxL1uf7D1.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::method5KzWu5VzxL1uf7D1
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
method5KzWu5VzxL1uf7D1.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: method5KzWu5VzxL1uf7D1.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::method5KzWu5VzxL1uf7D1
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
    const method5KzWu5VzxL1uf7D1Form = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: method5KzWu5VzxL1uf7D1.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::method5KzWu5VzxL1uf7D1
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
        method5KzWu5VzxL1uf7D1Form.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: method5KzWu5VzxL1uf7D1.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::method5KzWu5VzxL1uf7D1
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/{id}/download'
 */
        method5KzWu5VzxL1uf7D1Form.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: method5KzWu5VzxL1uf7D1.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    method5KzWu5VzxL1uf7D1.form = method5KzWu5VzxL1uf7D1Form
/**
* @see \App\Http\Controllers\ActionApplicationController::AjJFTVcuIIOWDKAU
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
export const AjJFTVcuIIOWDKAU = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: AjJFTVcuIIOWDKAU.url(args, options),
    method: 'get',
})

AjJFTVcuIIOWDKAU.definition = {
    methods: ["get","head"],
    url: '/action/applications/eligible-applicants/{batchId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::AjJFTVcuIIOWDKAU
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
AjJFTVcuIIOWDKAU.url = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { batchId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    batchId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        batchId: args.batchId,
                }

    return AjJFTVcuIIOWDKAU.definition.url
            .replace('{batchId}', parsedArgs.batchId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::AjJFTVcuIIOWDKAU
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
AjJFTVcuIIOWDKAU.get = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: AjJFTVcuIIOWDKAU.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::AjJFTVcuIIOWDKAU
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
AjJFTVcuIIOWDKAU.head = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: AjJFTVcuIIOWDKAU.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::AjJFTVcuIIOWDKAU
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
    const AjJFTVcuIIOWDKAUForm = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: AjJFTVcuIIOWDKAU.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::AjJFTVcuIIOWDKAU
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
        AjJFTVcuIIOWDKAUForm.get = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: AjJFTVcuIIOWDKAU.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::AjJFTVcuIIOWDKAU
 * @see app/Http/Controllers/ActionApplicationController.php:101
 * @route '/action/applications/eligible-applicants/{batchId}'
 */
        AjJFTVcuIIOWDKAUForm.head = (args: { batchId: string | number } | [batchId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: AjJFTVcuIIOWDKAU.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    AjJFTVcuIIOWDKAU.form = AjJFTVcuIIOWDKAUForm
/**
* @see \App\Http\Controllers\ActionApplicationController::OhDy9OQB90hOSmxy
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
export const OhDy9OQB90hOSmxy = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: OhDy9OQB90hOSmxy.url(options),
    method: 'post',
})

OhDy9OQB90hOSmxy.definition = {
    methods: ["post"],
    url: '/action/applications/check-eligibility',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::OhDy9OQB90hOSmxy
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
OhDy9OQB90hOSmxy.url = (options?: RouteQueryOptions) => {
    return OhDy9OQB90hOSmxy.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::OhDy9OQB90hOSmxy
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
OhDy9OQB90hOSmxy.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: OhDy9OQB90hOSmxy.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::OhDy9OQB90hOSmxy
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
    const OhDy9OQB90hOSmxyForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: OhDy9OQB90hOSmxy.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::OhDy9OQB90hOSmxy
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/check-eligibility'
 */
        OhDy9OQB90hOSmxyForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: OhDy9OQB90hOSmxy.url(options),
            method: 'post',
        })
    
    OhDy9OQB90hOSmxy.form = OhDy9OQB90hOSmxyForm
/**
* @see \App\Http\Controllers\ActionApplicationController::r48nBNTFADishNkz
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
export const r48nBNTFADishNkz = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: r48nBNTFADishNkz.url(options),
    method: 'get',
})

r48nBNTFADishNkz.definition = {
    methods: ["get","head"],
    url: '/action/applications/users/interviewers',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::r48nBNTFADishNkz
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
r48nBNTFADishNkz.url = (options?: RouteQueryOptions) => {
    return r48nBNTFADishNkz.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::r48nBNTFADishNkz
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
r48nBNTFADishNkz.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: r48nBNTFADishNkz.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::r48nBNTFADishNkz
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
r48nBNTFADishNkz.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: r48nBNTFADishNkz.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::r48nBNTFADishNkz
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
    const r48nBNTFADishNkzForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: r48nBNTFADishNkz.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::r48nBNTFADishNkz
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
        r48nBNTFADishNkzForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: r48nBNTFADishNkz.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::r48nBNTFADishNkz
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/users/interviewers'
 */
        r48nBNTFADishNkzForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: r48nBNTFADishNkz.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    r48nBNTFADishNkz.form = r48nBNTFADishNkzForm
/**
* @see \App\Http\Controllers\ActionApplicationController::Vqj9ElBWe2BJk6km
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
export const Vqj9ElBWe2BJk6km = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Vqj9ElBWe2BJk6km.url(args, options),
    method: 'get',
})

Vqj9ElBWe2BJk6km.definition = {
    methods: ["get","head"],
    url: '/action/applications/previous/{applicantId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActionApplicationController::Vqj9ElBWe2BJk6km
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
Vqj9ElBWe2BJk6km.url = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { applicantId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    applicantId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        applicantId: args.applicantId,
                }

    return Vqj9ElBWe2BJk6km.definition.url
            .replace('{applicantId}', parsedArgs.applicantId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActionApplicationController::Vqj9ElBWe2BJk6km
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
Vqj9ElBWe2BJk6km.get = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Vqj9ElBWe2BJk6km.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ActionApplicationController::Vqj9ElBWe2BJk6km
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
Vqj9ElBWe2BJk6km.head = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Vqj9ElBWe2BJk6km.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ActionApplicationController::Vqj9ElBWe2BJk6km
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
    const Vqj9ElBWe2BJk6kmForm = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: Vqj9ElBWe2BJk6km.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ActionApplicationController::Vqj9ElBWe2BJk6km
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
        Vqj9ElBWe2BJk6kmForm.get = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Vqj9ElBWe2BJk6km.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ActionApplicationController::Vqj9ElBWe2BJk6km
 * @see app/Http/Controllers/ActionApplicationController.php:0
 * @route '/action/applications/previous/{applicantId}'
 */
        Vqj9ElBWe2BJk6kmForm.head = (args: { applicantId: string | number } | [applicantId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: Vqj9ElBWe2BJk6km.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    Vqj9ElBWe2BJk6km.form = Vqj9ElBWe2BJk6kmForm
const generated = {
    0xDyEK08Y4TXtmK0: Object.assign(method0xDyEK08Y4TXtmK0, method0xDyEK08Y4TXtmK0),
rUHQm3gNskUeCDoX: Object.assign(rUHQm3gNskUeCDoX, rUHQm3gNskUeCDoX),
5KzWu5VzxL1uf7D1: Object.assign(method5KzWu5VzxL1uf7D1, method5KzWu5VzxL1uf7D1),
AjJFTVcuIIOWDKAU: Object.assign(AjJFTVcuIIOWDKAU, AjJFTVcuIIOWDKAU),
OhDy9OQB90hOSmxy: Object.assign(OhDy9OQB90hOSmxy, OhDy9OQB90hOSmxy),
r48nBNTFADishNkz: Object.assign(r48nBNTFADishNkz, r48nBNTFADishNkz),
Vqj9ElBWe2BJk6km: Object.assign(Vqj9ElBWe2BJk6km, Vqj9ElBWe2BJk6km),
}

export default generated