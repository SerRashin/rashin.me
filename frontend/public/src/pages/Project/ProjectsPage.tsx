import {useState, useEffect, useContext, CSSProperties} from 'react';
import { Container, Row, Button } from 'react-bootstrap';
import { ThemeContext } from 'styled-components';
import {Fade} from 'react-awesome-reveal';
import Header from "../../components/Header";
import FallbackSpinner from "../../components/FallbackSpinner";
import ApiService, {PaginatedCollection, Project} from "../../services/ApiService";
import ProjectCard from "./ProjectCard";

const styles: {[key: string]: CSSProperties} = {
  containerStyle: {
    marginBottom: 25,
  },
  showMoreStyle: {
    margin: 25,
  },
};

interface ProjectsProps {
  header: string,
}

const ProjectsPage = ({ header }: ProjectsProps) => {
  const theme = useContext(ThemeContext);
  const [showMore, setShowMore] = useState(false);
  const [offset, setOffset] = useState(0);
  const [limit, setLimit] = useState(6);
  const [data, setData] = useState<PaginatedCollection<Project> | null>(null);

  const loadData = () => {
    ApiService.getProjects(limit, offset)
      .then((response: PaginatedCollection<Project>) => {
        setData(response);

        const pagination = response.pagination;

        setOffset(pagination.offset);
        setLimit(pagination.limit);

        setShowMore(pagination.offset + response.data.length < pagination.total);
      });
  };

  useEffect( () => {
    loadData();
  }, [offset, limit, showMore]);


  return (
    <>
      <Header title={header} />
  {data
    ? (
      <div className="section-content-container">
      <Container style={styles.containerStyle}>
      <Row xs={1} sm={1} md={2} lg={3} className="g-4">
    {data.data?.map((project: Project) => (
        <Fade key={project.id}>
        <ProjectCard project={project} />
  </Fade>
  ))}
    </Row>

    {showMore
    && (
      <Button
        style={styles.showMoreStyle}
      variant={theme.bsSecondaryVariant}
      onClick={() => setOffset(offset + limit)}
    >
      show more
    </Button>
    )}
    </Container>
    </div>
  ) : <FallbackSpinner /> }
  </>
);
};

export default ProjectsPage;
