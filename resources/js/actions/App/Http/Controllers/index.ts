import AuthController from './AuthController'
import DashboardController from './DashboardController'
import ResourceScheduleController from './ResourceScheduleController'
import Settings from './Settings'


const Controllers = {
    AuthController: Object.assign(AuthController, AuthController),
    DashboardController: Object.assign(DashboardController, DashboardController),
    ResourceScheduleController: Object.assign(ResourceScheduleController, ResourceScheduleController),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers