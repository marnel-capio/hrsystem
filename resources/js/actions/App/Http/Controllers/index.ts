import AuthController from './AuthController'
import ForgotPasswordController from './ForgotPasswordController'
import DashboardController from './DashboardController'
import IntermediateApplicationController from './IntermediateApplicationController'
import ApplicationImportController from './ApplicationImportController'
import ActionApplicantProgrammingLanguageController from './ActionApplicantProgrammingLanguageController'
import UserController from './UserController'
import ResourceScheduleController from './ResourceScheduleController'
import ActionApplicationController from './ActionApplicationController'
import ActionBatchController from './ActionBatchController'
import ActionApplicantController from './ActionApplicantController'
import IntermediateProjectController from './IntermediateProjectController'
import Settings from './Settings'
const Controllers = {
    AuthController: Object.assign(AuthController, AuthController),
ForgotPasswordController: Object.assign(ForgotPasswordController, ForgotPasswordController),
DashboardController: Object.assign(DashboardController, DashboardController),
IntermediateApplicationController: Object.assign(IntermediateApplicationController, IntermediateApplicationController),
ApplicationImportController: Object.assign(ApplicationImportController, ApplicationImportController),
ActionApplicantProgrammingLanguageController: Object.assign(ActionApplicantProgrammingLanguageController, ActionApplicantProgrammingLanguageController),
UserController: Object.assign(UserController, UserController),
ResourceScheduleController: Object.assign(ResourceScheduleController, ResourceScheduleController),
ActionApplicationController: Object.assign(ActionApplicationController, ActionApplicationController),
ActionBatchController: Object.assign(ActionBatchController, ActionBatchController),
ActionApplicantController: Object.assign(ActionApplicantController, ActionApplicantController),
IntermediateProjectController: Object.assign(IntermediateProjectController, IntermediateProjectController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers