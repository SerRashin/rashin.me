import {CSSProperties, useContext} from 'react';
import {
  Button, Card, Badge, Col,
} from 'react-bootstrap';
import { ThemeContext } from 'styled-components';
import ReactMarkdown from 'react-markdown';
import {Link, Project} from "../../services/ApiService";

const styles: {[key: string]: CSSProperties} = {
  badgeStyle: {
    paddingLeft: 10,
    paddingRight: 10,
    paddingTop: 5,
    paddingBottom: 5,
    margin: 5,
  },
  cardStyle: {
    borderRadius: 10,
  },
  cardTitleStyle: {
    fontSize: 24,
    fontWeight: 700,
  },
  cardTextStyle: {
    textAlign: 'left',
    marginBottom: '-1rem',
    padding:0,
  },
  linkStyle: {
    textDecoration: 'none',
    padding: 10,
  },
  buttonStyle: {
    margin: 5,
  },
};

interface ProjectProps {
  project: Project
}


const ProjectCard = ({ project }: ProjectProps) => {
  const theme = useContext(ThemeContext);

  return (
    <Col>
      <Card
        key={project.id}
        style={{
          ...styles.cardStyle,
          backgroundColor: theme.cardBackground,
          borderColor: theme.cardBorderColor,
        }}
        text={theme.bsSecondaryVariant}
      >
        <Card.Img variant="top" src={project.image.src} />
        <Card.Body>
          <Card.Title style={styles.cardTitleStyle}>{project.name}</Card.Title>
          <Card.Body style={styles.cardTextStyle}>
            <ReactMarkdown
              children={project.description}
              components={{
                p: 'span',
              }}
            />
          </Card.Body>
        </Card.Body>

        <Card.Body>
          {project.links?.map((link: Link) => (
            <Button
              key={link.id}
              style={styles.buttonStyle}
              variant={'outline-' + theme.bsSecondaryVariant}
              onClick={() => window.open(link.url, '_blank')}
            >
              {link.title}
            </Button>
          ))}
        </Card.Body>
        {project.tags && (
          <Card.Footer style={{ backgroundColor: theme.cardFooterBackground }}>
            {project.tags.map((tag: string) => (
              <Badge
                key={tag}
                pill
                bg={theme.bsSecondaryVariant}
                text={theme.bsPrimaryVariant}
                style={styles.badgeStyle}
              >
                {tag}
              </Badge>
            ))}
          </Card.Footer>
        )}
      </Card>
    </Col>
  );
};

export default ProjectCard;
