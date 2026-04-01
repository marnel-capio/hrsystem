import AuthController from './AuthController'
import ForgotPasswordController from './ForgotPasswordController'
import DashboardController from './DashboardController'
import UserController from './UserController'
import ResourceScheduleController from './ResourceScheduleController'
import ApplicationImportController from './ApplicationImportController'
import ActionBatchController from './ActionBatchController'
import ActionApplicantController from './ActionApplicantController'
import IntermediateProjectController from './IntermediateProjectController'
import IntermediateRequisitionController from './IntermediateRequisitionController'
import Settings from './Settings'
const Controllers = {
    AuthController: Object.assign(AuthController, AuthController),
ForgotPasswordController: Object.assign(ForgotPasswordController, ForgotPasswordController),
DashboardController: Object.assign(DashboardController, DashboardController),
UserController: Object.assign(UserController, UserController),
ResourceScheduleController: Object.assign(ResourceScheduleController, ResourceScheduleController),
ApplicationImportController: Object.assign(ApplicationImportController, ApplicationImportController),
ActionBatchController: Object.assign(ActionBatchController, ActionBatchController),
ActionApplicantController: Object.assign(ActionApplicantController, ActionApplicantController),
IntermediateProjectController: Object.assign(IntermediateProjectController, IntermediateProjectController),
IntermediateRequisitionController: Object.assign(IntermediateRequisitionController, IntermediateRequisitionController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers