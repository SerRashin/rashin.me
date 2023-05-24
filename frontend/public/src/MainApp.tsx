import { Suspense } from 'react';
import { Routes, Route } from 'react-router-dom';
import FallbackSpinner from './components/FallbackSpinner';
import NotFound from "./components/NotFound";
import ExperiencePage from "./pages/Experience/ExperiencePage";
import HomePage from "./pages/Home/HomePage";
import AboutPage from "./pages/About/AboutPage";
import EducationPage from "./pages/Education/EducationPage";
import ProjectsPage from "./pages/Project/ProjectsPage";
import SkillPage from "./pages/Skill/SkillPage";

function MainApp() {
  return (
      <main className="main">
          <Suspense fallback={<FallbackSpinner />}>
              <Routes>
                  <Route path="/" element={<HomePage />} />
                  <Route path="/about" element={<AboutPage header='About'/>} />
                  <Route path="/skills" element={<SkillPage header='Skills'/>} />
                  <Route path="/education" element={<EducationPage header='Education'/>} />
                  <Route path="/experience" element={<ExperiencePage header='Experience'/>} />
                  <Route path="/projects" element={<ProjectsPage header='Projects'/>} />
                  <Route path="*" element={<NotFound />} />
              </Routes>
          </Suspense>
      </main>
  );
}

export default MainApp;


