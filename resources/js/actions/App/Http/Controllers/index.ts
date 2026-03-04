import AuthController from './AuthController'
import ForgotPasswordController from './ForgotPasswordController'
import DashboardController from './DashboardController'
import ActionBatchController from './ActionBatchController'
import ResourceScheduleController from './ResourceScheduleController'
import Settings from './Settings'
const Controllers = {
    AuthController: Object.assign(AuthController, AuthController),
ForgotPasswordController: Object.assign(ForgotPasswordController, ForgotPasswordController),
DashboardController: Object.assign(DashboardController, DashboardController),
ActionBatchController: Object.assign(ActionBatchController, ActionBatchController),
ResourceScheduleController: Object.assign(ResourceScheduleController, ResourceScheduleController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers