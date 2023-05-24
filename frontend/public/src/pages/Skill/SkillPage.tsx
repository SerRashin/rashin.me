import {CSSProperties, useEffect, useState} from 'react';
import ReactMarkdown from 'react-markdown';
import PropTypes from 'prop-types';
import { Fade } from 'react-awesome-reveal';
import { Container } from 'react-bootstrap';
import ApiService, {PaginatedCollection, Section, Skill} from "../../services/ApiService";
import Header from "../../components/Header";
import FallbackSpinner from "../../components/FallbackSpinner";

const headerText = 'I love to learn new things and experiment with new technologies.' +
  'These are some of the major languages, technologies, tools and platforms I have worked with:';

const styles: {[key: string]: CSSProperties} = {
  iconStyle: {
    height: 75,
    width: 75,
    margin: 10,
    marginBottom: 0,
  },
  introTextContainer: {
    whiteSpace: 'pre-wrap',
  },
};

interface SkillsProps {
  header: string,
}

function Skills({ header }: SkillsProps) {
  const [data, setData] = useState<PaginatedCollection<Section> | null>(null);

  useEffect(() => {
    ApiService.getSections()
      .then((response: PaginatedCollection<Section>) => {
        setData(response)
      });
  }, []);

  const renderSkillsIntro = (intro: string) => (
    <h4 style={styles.introTextContainer}>
      <ReactMarkdown children={intro} />
    </h4>
  );

  return (
    <>
      <Header title={header} />
        {data ? (
          <Fade>
            <div className="section-content-container">
              <Container>
                {renderSkillsIntro(headerText)}
                {data.data?.map((section: Section) => (
                  <div key={section.id}>
                    <br />
                    <h3>{section.name}</h3>
                      {section.skills.map((skill: Skill) => (
                        <div key={skill.id} style={{ display: 'inline-block' }}>
                          <img
                            style={styles.iconStyle}
                            src={skill.image.src}
                            alt={skill.name}
                          />
                          <p>{skill.name}</p>
                        </div>
                      ))}
                  </div>
                ))}
              </Container>
            </div>
          </Fade>
        ) : <FallbackSpinner /> }
    </>
  );
}

Skills.propTypes = {
  header: PropTypes.string.isRequired,
};

export default Skills;
