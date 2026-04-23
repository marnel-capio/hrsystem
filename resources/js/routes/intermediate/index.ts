import projects from './projects'
import requisitions from './requisitions'
import applications from './applications'
import applicants from './applicants'
const intermediate = {
    projects: Object.assign(projects, projects),
requisitions: Object.assign(requisitions, requisitions),
applications: Object.assign(applications, applications),
applicants: Object.assign(applicants, applicants),
}

export default intermediate